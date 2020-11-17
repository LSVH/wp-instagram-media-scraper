<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\StatisticsSection;

abstract class AbstractScraper implements Scraper
{
    protected $domain;
    protected $statistics;
    protected $settings;
    protected $author;

    public function __construct($domain, $statistics, $settings)
    {
        $this->domain = $domain;
        $this->statistics = $statistics;
        $this->settings = $settings;
    }

    protected function createAttachments($items)
    {
        $total = $this->statistics->getValue(StatisticsSection::FIELD_TOTAL, 0);

        foreach ($items as $item) {
            if ($this->createAttachment($item)) {
                $total += 1;
            }
        }

        $this->statistics->setValue(StatisticsSection::FIELD_TOTAL, $total);
        $this->statistics->setValue(StatisticsSection::FIELD_LAST, time());
    }

    protected function createAttachment($item)
    {
        if ($this->isMediaUploaded($item->getSlug())) {
            return false;
        }

        $media = $this->downloadMedia($item);
        if (is_wp_error($media)) {
            $this->addDefaultErrorMessage($media, $item);

            return false;
        }

        $attachment = $this->uploadMedia($item, $media);
        if (is_wp_error($attachment)) {
            $this->addDefaultErrorMessage($attachment, $item);

            return false;
        }

        return true;
    }

    protected function isMediaUploaded($slug)
    {
        $query = new \WP_Query([
            'post_status' => 'any',
            'post_type' => 'attachment',
            'author' => $this->getAuthor(),
            'name' => $slug,
        ]);

        return $query->have_posts();
    }

    protected function getAuthor()
    {
        if (!empty($this->author)) {
            return $this->author;
        }

        if (is_int($this->author = username_exists($this->domain))) {
            return $this->author;
        }

        if (is_int($this->author = wp_create_user($this->domain, wp_generate_password(64)))) {
            return $this->author;
        }

        return null;
    }

    public function downloadMedia($item)
    {
        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $url = $item->getResource();
        $basename = basename(strtok($url, '?'));
        $ext = pathinfo($basename, PATHINFO_EXTENSION);
        $name = $item->getSlug() . '.' . $ext;
        $download = download_url($url);

        return is_string($download) ? [
            'name' => $name,
            'tmp_name' => $download,
        ] : $download;
    }

    public function uploadMedia($item, $media)
    {
        if (!function_exists('media_handle_sideload')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $source = $item->getSource();
        $source = '<a href="' . $source . '" target="_blank">' . $source . '</a>';
        $source = sprintf(__('Source: %s', $this->domain), $source);
        $source = '<p class="source-reference">' . $source . '</p>';

        $attachment = [
            'post_name' => $item->getSlug(),
            'post_date' => $item->getDate(),
            'post_title' => $item->getTitle(),
            'post_content' => $item->getContent() . $source,
            'post_author' => $this->getAuthor(),
        ];

        return media_handle_sideload($media, 0, null, $attachment);
    }

    private function addDefaultErrorMessage($error, $item)
    {
        $domain = $this->domain;
        $id = $this->getId();
        $link = sprintf('<a href="%s" target="_blank">%s</a>', $item->getSource(), __('this item', $domain));
        $code = $error->get_error_code();
        $errmsg = $error->get_error_message($code);
        $message = sprintf(__('While downloading %s with the `%s`, the following error occurred: %s', $domain), $link, $id, $errmsg);
        add_settings_error($domain, $code, $message);
    }
}

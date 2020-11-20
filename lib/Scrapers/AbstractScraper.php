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

    protected function storeMedias($medias)
    {
        $current = 0;
        $total = $this->statistics->getValue(StatisticsSection::FIELD_TOTAL, 0);

        foreach ($medias as $media) {
            if ($this->storeMedia($media)) {
                $current += 1;
                $total += 1;
            }
        }
        $this->addDefaultInfoMessage($current);
        $this->statistics->setValue(StatisticsSection::FIELD_TOTAL, intval($total));
        $this->statistics->setValue(StatisticsSection::FIELD_LAST, intval(time()));
    }

    protected function storeMedia($media)
    {
        if ($this->isMediaUploaded($media->getId())) {
            return false;
        }

        $download = $this->downloadMedia($media);
        if (is_wp_error($file)) {
            $this->addDefaultErrorMessage($media, $download);

            return false;
        }

        $upload = $this->uploadMedia($media, $download);
        if (is_wp_error($upload)) {
            $this->addDefaultErrorMessage($media, $upload);

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

    public function downloadMedia($media)
    {
        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $url = $media->getResource();
        $basename = basename(strtok($url, '?'));
        $ext = pathinfo($basename, PATHINFO_EXTENSION);
        $name = $media->getId() . '.' . $ext;
        $download = download_url($url);

        return is_string($download) ? [
            'name' => $name,
            'tmp_name' => $download,
        ] : $download;
    }

    public function uploadMedia($media, $download)
    {
        if (!function_exists('media_handle_sideload')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $source = $media->getSource();
        $source = '<a href="' . $source . '" target="_blank">' . $source . '</a>';
        $source = sprintf(__('Source: %s', $this->domain), $source);
        $source = '<p class="source-reference">' . $source . '</p>';

        $attachment = [
            'post_name' => $media->getId(),
            'post_date' => $media->getDate(),
            'post_title' => $media->getTitle(),
            'post_content' => $media->getContent() . $source,
            'post_author' => $this->getAuthor(),
        ];

        return media_handle_sideload($download, 0, null, $attachment);
    }

    private function addDefaultErrorMessage($media, $error)
    {
        $domain = $this->domain;
        $id = $this->getId();
        $link = sprintf('<a href="%s" target="_blank">%s</a>', $media->getSource(), __('this item', $domain));
        $code = $error->get_error_code();
        $errmsg = $error->get_error_message($code);
        $message = sprintf(__('While downloading %s with the `%s`, the following error occurred: %s', $domain), $link, $id, $errmsg);
        add_settings_error($domain, $code, $message);
    }

    private function addDefaultInfoMessage($count)
    {
        $domain = $this->domain;
        $id = $this->getId();
        $date = date('Y-m-d H:i:s', time());
        $count = sprintf(_n('%s item', '%s items', $count, $domain), $count);
        $message = sprintf(__('Ran the `%s` on `%s` and retrieved %s', $domain), $id, $date, $count);
        add_settings_error($domain, 'info', $message, 'info');
    }
}

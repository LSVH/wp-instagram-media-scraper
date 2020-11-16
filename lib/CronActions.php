<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper;

use \InstagramScraper\Instagram;
use \GuzzleHttp\Client;

class CronActions
{
    private $app;
    private $options;
    private $hook;
    private $scraper;

    public function __construct($app, $options)
    {
        $this->app = $app;
        $this->options = $options;
        $this->hook = $app->getDomain() . '_cron_hook';
        $this->scraper = username_exists($app->getDomain());
    }

    public function init()
    {
        $this->registerHook();
        $this->scheduleEvent();
        $this->registerManualEvent();
    }

    private function getScraper()
    {
        if (empty($this->scraper)) {
            $this->scraper = wp_create_user($this->app->getDomain(), wp_generate_password(24));
        }

        return $this->scraper;
    }

    private function registerHook()
    {
        add_action($this->hook, function () {
            $medias = $this->getMedias();
            $this->createMedias($medias);
        });
    }

    private function scheduleEvent()
    {
        if (!wp_next_scheduled($this->hook)) {
            $interval = $this->options->getValue(Options::IG_INTERVAL, 'daily');

            wp_schedule_event(time(), $interval, $this->hook);
        }
    }

    private function registerManualEvent()
    {
        $domain = $this->app->getDomain();
        $callback = function () use ($domain) {
            $values = array_key_exists($domain, $_POST) ? $_POST[$domain] : [];
            $value = array_key_exists(Options::RUN, $values) ? $values[Options::RUN] : null;
            if (!empty($value)) {
                $_POST = array();
                do_action($this->hook);
            }
        };

        add_action("add_option", $callback);
        add_action("update_option", $callback);
    }

    private function getMedias()
    {
        $username = $this->options->getValue(Options::IG_USERNAME);
        $amount = $this->options->getValue(Options::IG_AMOUNT, 10);
        $instagram = new Instagram(new Client());

        return empty($username) ? [] : $instagram->getMedias($username, $amount);
    }

    private function createMedias($medias)
    {
        $count = intval($this->options->getValue(Options::COUNT, 0));

        foreach ($medias as $media) {
            $result = $this->uploadMedia($media);

            if ($result === 'uploaded') {
                $count += 1;
            }
        }

        $this->options->setValue(Options::COUNT, $count);
        $this->options->setValue(Options::LAST, time());
    }

    private function uploadMedia($media)
    {
        $is_image = $media->getType() !== 'video';

        $url = $is_image ? $media->getImageHighResolutionUrl() : $media->getVideoStandardResolutionUrl();
        $basename = wp_basename(strtok($url, '?'));
        $extension = pathinfo($basename,  PATHINFO_EXTENSION);
        $domain = $this->app->getDomain();
        $slug =  $domain . '_' . $media->getId();
        $name = $slug . '.' . $extension;
        $date = date('Y-m-d H:i:s', $media->getCreatedTime());

        if ($this->isMediaUploaded($slug)) {
            return 'skipped';
        }

        if (!function_exists('download_url')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        $tmp_name = download_url($url);

        $file_attrs = [
            'name' => $name,
            'tmp_name' => $tmp_name,
        ];

        if (is_wp_error($tmp_name)) {
            $this->addSettingsError($tmp_name, $media);

            return false;
        }

        $post_attrs = [
            'post_name' => $slug,
            'post_date' => $date,
            'post_modified' => $date,
            'guid' => $media->getLink(),
            'post_title' => $media->getId(),
            'post_content' => $media->getCaption(),
            'post_author' => $this->getScraper(),
        ];

        if (!function_exists('media_handle_sideload')) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
        }

        $result = media_handle_sideload($file_attrs, 0, null, $post_attrs);

        if (is_wp_error($result)) {
            $this->addSettingsError($result, $media);

            return false;
        }

        return 'uploaded';
    }

    private function isMediaUploaded($slug)
    {
        $query = new \WP_Query([
            'post_status' => 'any',
            'post_type' => 'attachment',
            'author' => $this->getScraper(),
            'name' => $slug,
        ]);

        return $query->have_posts();
    }

    private function addSettingsError($error, $media)
    {
        $domain = $this->app->getDomain();
        $code = $error->get_error_code();
        $msg = $error->get_error_message();
        $post = $media->getLink();
        $message = sprintf(__('While scraping media from <a href="%s" target="_blank">this post</a> the following error occurred: "%s"', $domain), $post, $msg);

        if (!function_exists('add_settings_error')) {
            require_once ABSPATH . 'wp-admin/includes/template.php';
        }

        add_settings_error($domain, $code, $message);
    }
}

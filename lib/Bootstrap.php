<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper;

use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\ScraperInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingPageInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingDataInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingSectionInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers\InstagramScraper;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\InstagramSection;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\StatisticsSection;

class Bootstrap
{
    private $domain;
    private $options;

    public function __construct($file)
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $data = get_plugin_data($file, false);
        $this->domain = array_key_exists('TextDomain', $data) ? $data['TextDomain'] : 'default';
        $this->options = get_option(esc_sql($this->domain), []);
    }

    public function exec()
    {
        $domain = $this->domain;
        $options = $this->options;
        $instagram = new InstagramSection($domain, $options);
        $statistics = new StatisticsSection($domain, $options);
        $sections = [$instagram, $statistics];
        $scrapers = [new InstagramScraper($domain, $statistics, $instagram)];

        add_action('init', function () use ($domain, $scrapers) {
            ScraperInstaller::install($domain, $scrapers);
        });

        add_action('admin_menu', function () use ($domain) {
            SettingPageInstaller::install($domain, ['icon' => 'share']);
        });

        add_action('admin_init', function () use ($domain, $sections) {
            SettingDataInstaller::install($domain);
            SettingSectionInstaller::install($domain, $sections);
        });
    }
}

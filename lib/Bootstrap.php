<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper;

use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\SectionFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\ScraperFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\ScraperInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingPageInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingDataInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingSectionInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Installers\SettingValidatorInstaller;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\InstagramSection;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\StatisticsSection;
use LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers\InstagramScraper;

class Bootstrap
{
    private $domain;
    private $options;
    private $stats;

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
        $sectionArgs = $this->getSectionArgs();
        $instagram = SectionFactory::createInstance(InstagramSection::class, $sectionArgs);
        $this->stats = SectionFactory::createInstance(StatisticsSection::class, $sectionArgs);
        $sections = [$instagram, $this->stats];

        $scrapers = [
            ScraperFactory::createInstance(InstagramScraper::class, $this->getScraperArgs($instagram))
        ];

        $domain = $this->domain;
        add_action('init', function () use ($domain, $scrapers) {
            ScraperInstaller::install($domain, $scrapers);
        });

        add_action('admin_menu', function () use ($domain) {
            SettingPageInstaller::install($domain, ['icon' => 'share']);
        });

        add_action('admin_init', function () use ($domain, $sections) {
            SettingDataInstaller::install($domain);
            SettingSectionInstaller::install($domain, $sections);
            SettingValidatorInstaller::install($domain, $sections);
        });
    }

    private function getSectionArgs()
    {
        return [
            SectionFactory::ATTR_DOMAIN => $this->domain,
            SectionFactory::ATTR_ARGS => $this->options,
        ];
    }

    private function getScraperArgs($data)
    {
        return [
            ScraperFactory::ATTR_DOMAIN => $this->domain,
            ScraperFactory::ATTR_STATS => $this->stats,
            ScraperFactory::ATTR_DATA => $data,
        ];
    }
}

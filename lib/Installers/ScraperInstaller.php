<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class ScraperInstaller implements Installer
{
    public static function install($domain, $options = [])
    {
        foreach ($options as $scraper) {
            $hook = Utilities::prefix($domain, $scraper->getId());
            static::registerActionHook($hook, $scraper);
            static::registerScheduledEvent($hook, $scraper);
            static::registerOptionSavedEvent($domain, $hook);
        }
    }

    private static function registerActionHook($hook, $scraper)
    {
        add_action($hook, function () use ($scraper) {
            $scraper->execute();
        });
    }

    private static function registerScheduledEvent($hook, $scraper)
    {
        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time(), $scraper->getInterval(), $hook);
        }
    }

    private static function registerOptionSavedEvent($domain, $hook)
    {
        $callback = function () use ($domain, $hook) {
            $action = SettingPageInstaller::FIELD_EXEC;
            $settings = Utilities::getArrayValueByKey($_POST, $domain, []);
            $value = Utilities::getArrayValueByKey($settings, $action, null);
            if (!empty($value)) {
                unset($_POST[$domain][$action]);
                do_action($hook);
            }
        };

        add_action("add_option", $callback);
        add_action("update_option", $callback);
    }
}

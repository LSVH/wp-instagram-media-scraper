<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class ScraperInstaller implements Installer
{
    public static function install($domain, $args = [])
    {
        foreach ($args as $scraper) {
            $hook = Utilities::prefix($domain, $scraper->getId());
            static::registerActionHook($hook, $scraper);
            static::registerScheduledEvent($hook, $scraper);
            static::registerOptionSavedEvent($hook, $domain);
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

    private static function registerOptionSavedEvent($hook, $domain)
    {
        $callback = function () use ($hook, $domain) {
            $exec = SettingPageInstaller::FIELD_EXEC;
            $action = Utilities::prefix($domain, $exec);
            $settings = Utilities::getArrayValueByKey($_POST, $domain, []);
            $nonce = Utilities::getArrayValueByKey($settings, $exec, null);
            if (wp_verify_nonce($nonce, $action)) {
                unset($_POST[$domain][$exec]);
                do_action($hook);
            }
        };

        add_action("add_option", $callback);
        add_action("update_option", $callback);
    }
}

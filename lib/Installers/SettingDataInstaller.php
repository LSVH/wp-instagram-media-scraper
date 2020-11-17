<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

class SettingDataInstaller implements Installer
{
    public static function install($domain, $options = [])
    {
        register_setting($domain, $domain, array_merge($options, [
            'type' => 'array',
            'sanitize_callback' => function ($value) use ($domain) {
                /** 
                 * Triggers before the setting is saved.
                 * 
                 * @since 1.0.0
                 * 
                 * @param mixed $value The value of the setting. 
                 */
                return apply_filters("validate_setting_$domain", $value);
            }
        ]));
    }
}

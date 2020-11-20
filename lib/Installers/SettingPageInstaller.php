<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\RendererFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\IconTextRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\CheckboxFieldRenderer;

class SettingPageInstaller implements Installer
{
    const FIELD_EXEC = 'execute-all';

    public static $capability = 'manage_options';

    public static function install($domain, $args = [])
    {
        $pageTitle = static::getPageTitle($domain);
        $menuTitle = static::getMenuTitle($domain, $args);

        add_options_page($pageTitle, $menuTitle, static::$capability, $domain, function () use ($pageTitle, $domain) {
            $title = "<h1>$pageTitle</h1><hr />";
            $form = static::getForm($domain);

            print $title . $form;
        });
    }

    private static function getPageTitle($content)
    {
        return ucfirst(preg_replace('/[_-]/', ' ', $content));
    }

    private static function getMenuTitle($content, $attrs = [])
    {
        $instance = RendererFactory::createInstance(IconTextRenderer::class, $attrs);
        $content = ucfirst(substr(strstr(static::getPageTitle($content), ' '), 1));

        return $instance->render($content);
    }

    private static function getForm($domain)
    {
        $fields = static::getFormFields($domain);
        $actions = static::getFormActions($domain);

        return '<form action="options.php" method="post">' . $fields . $actions . '</form>';
    }

    private static function getFormFields($domain)
    {
        ob_start();
        settings_fields($domain);
        do_settings_sections($domain);
        return ob_get_clean();
    }

    private static function getFormActions($domain)
    {
        $exec = static::FIELD_EXEC;
        $action = Utilities::prefix($domain, $exec);
        $instance = RendererFactory::createInstance(CheckboxFieldRenderer::class, [
            'name' => Utilities::prefix($domain, $exec, '[', ']'),
            'class' => 'tagchecklist',
            'options' => [
                wp_create_nonce($action) => __('Check to execute all scrapers.', $domain),
            ]
        ]);

        $checkbox = $instance->render(null);
        $submit = get_submit_button(null, 'primary', 'submit', false);

        return "<p>$submit$checkbox</p>";
    }
}

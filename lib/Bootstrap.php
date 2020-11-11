<?php

namespace LSVH\WordPress\Plugin\InstagramMediaScraper;

class Bootstrap
{
    private $app;
    private $options;

    public function __construct($file)
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        $data = get_plugin_data($file, false);
        $name = array_key_exists('Name', $data) ? $data['Name'] : 'Default';
        $domain = array_key_exists('TextDomain', $data) ? $data['TextDomain'] : 'default';
        $this->app = new App($name, $domain);
        $this->options = new Options($this->app);
    }

    public function exec()
    {
        $this->setupActions();
        $this->setupSettingsPage();
    }

    private function setupActions()
    {
        add_action('admin_init', function () {
            $actions = new AdminActions($this->app, $this->options);
            $actions->init();
        });

        add_action('init', function () {
            $cronActions = new CronActions($this->app, $this->options);
            $cronActions->init();
        });
    }

    private function setupSettingsPage()
    {
        add_action('admin_menu', function () {
            add_options_page(
                $this->app->getTitle(),
                $this->getMenuTitle(),
                'manage_options',
                $this->app->getDomain(),
                function () {
                    $ui = new UserInterface($this->app, $this->options);
                    print $ui->render();
                }
            );
        });
    }

    private function getMenuTitle()
    {
        $title = preg_replace('/instagram\s+/i', '', $this->app->getTitle());
        return '<span class="dashicons dashicons-instagram"></span> ' . $title;
    }
}

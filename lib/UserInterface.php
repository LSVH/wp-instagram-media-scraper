<?php

namespace LSVH\WordPress\Plugin\InstagramMediaScraper;

class UserInterface
{
    private $app;
    private $options;

    public function __construct($app, $options)
    {
        $this->app = $app;
        $this->options = $options;
    }

    public function render()
    {
        $title = $this->renderTitle();
        $form = $this->renderForm();

        return $title . $form;
    }

    private function renderTitle()
    {
        $title = $this->app->getTitle();
        return "<h1>$title</h1>";
    }

    private function renderForm()
    {
        return '<form action="options.php" method="post">' . $this->renderInnerForm() . '</form>';
    }

    private function renderInnerForm()
    {
        $domain = $this->app->getDomain();
        ob_start();
        settings_fields($domain);
        do_settings_sections($domain);
        echo $this->renderFormActions();
        return ob_get_clean();
    }

    private function renderFormActions()
    {
        $submit = get_submit_button(null, 'primary', 'submit', false);
        $checkbox = $this->renderScrapeManuallyField();

        return "<p>$submit$checkbox</p>";
    }

    private function renderScrapeManuallyField()
    {
        $domain = $this->app->getDomain();
        $name = Options::RUN;
        $label = $this->options->getLabel($name);
        $nonce = wp_create_nonce("run_$domain");
        return '<label style="margin-left:12px"><input type="checkbox" name="' . $domain . '[' . $name . ']" value="' . $nonce . '" />' . $label . '</label>';
    }
}

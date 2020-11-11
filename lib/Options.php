<?php

namespace LSVH\WordPress\Plugin\InstagramMediaScraper;

class Options
{
    const USERNAME = 'username';
    const AMOUNT = 'amount';
    const INTERVAL = 'interval';
    const COUNT = 'count';
    const LAST = 'last';
    const RUN = 'run';

    private $app;
    private $values;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getLabel($name)
    {
        $domain = $this->app->getDomain();

        $labels = [
            static::USERNAME => __('Instagram username', $domain),
            static::AMOUNT => __('Scrape amount', $domain),
            static::INTERVAL => __('Scraping interval', $domain),
            static::COUNT => __('Media scraped', $domain),
            static::LAST => __('Last run', $domain),
            static::RUN => __('Check to start scraping after saving the settings.', $domain),
        ];

        return is_string($name) && array_key_exists($name, $labels) ? $labels[$name] : null;
    }

    public function getValue($name, $default = null)
    {
        $values = $this->getValues();

        return is_array($values) && array_key_exists($name, $values) && !empty(esc_attr($values[$name]))
            ? esc_attr($values[$name]) : $default;
    }

    public function setValue($name, $value)
    {
        $values = $this->getValues();
        $values[$name] = $value;
        $this->values = $values;
        update_option($this->app->getDomain(), $values);
    }

    public function getValues()
    {
        if (empty($this->values)) {
            $this->values = get_option($this->app->getDomain());
        }

        return $this->values;
    }
}

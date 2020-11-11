<?php

namespace LSVH\WordPress\Plugin\InstagramMediaScraper;

class AdminActions
{
    const SECTION_CONF = 'conf';
    const SECTION_STATS = 'stats';

    private $app;
    private $options;

    public function __construct($app, $options)
    {
        $this->app = $app;
        $this->options = $options;
    }

    public function init()
    {
        $this->registerSetting();
        $this->registerSettingSections();
        $this->registerSettingFields();
    }

    private function registerSetting()
    {
        register_setting($this->app->getDomain(), $this->app->getDomain());
    }

    private function registerSettingSections()
    {
        $domain = $this->app->getDomain();
        add_settings_section(static::SECTION_CONF, null, null, $domain);
        add_settings_section(static::SECTION_STATS, __('Statistics', $domain), null, $domain);
    }

    private function registerSettingFields()
    {
        $this->addInputField(static::SECTION_CONF, $this->options->getLabel(Options::USERNAME), Options::USERNAME, function ($name) {
            return $this->getInputField($name, 'text', ['regular-text', 'code']);
        });
        $this->addInputField(static::SECTION_CONF, $this->options->getLabel(Options::AMOUNT), Options::AMOUNT, function ($name) {
            return $this->getInputField($name, 'number', ['small-text'], ['min' => 1, 'max' => 50]);
        });
        $this->addInputField(static::SECTION_CONF, $this->options->getLabel(Options::INTERVAL), Options::INTERVAL, function ($name) {
            return $this->getSelectField($name, $this->getIntervalOptions());
        });
        $this->addInputField(static::SECTION_CONF, $this->options->getLabel(Options::INTERVAL), Options::INTERVAL, function ($name) {
            return $this->getSelectField($name, $this->getIntervalOptions());
        });
        $this->addInputField(static::SECTION_STATS, $this->options->getLabel(Options::COUNT), Options::COUNT, function ($name) {
            $value = $this->options->getValue($name);
            return empty($value) || !is_numeric($value) ? 0 : $value;
        });
        $this->addInputField(static::SECTION_STATS, $this->options->getLabel(Options::LAST), Options::LAST, function ($name) {
            $value = $this->options->getValue($name);
            $date = [
                date(get_option('date_format'), $value),
                __('at', $this->app->getDomain()),
                date(get_option('time_format'), $value)
            ];
            return empty($value) || !is_numeric($value) ? '-' : implode(' ', $date);
        });
    }

    private function getIntervalOptions()
    {
        $domain = $this->app->getDomain();

        return [
            'hourly' => __('Hourly', $domain),
            'twicedaily' => __('Twice daily', $domain),
            'daily' => __('Daily', $domain),
            'weekly' => __('Weekly', $domain),
        ];
    }

    private function addInputField($section, $title, $name, $callback)
    {
        add_settings_field($this->app->getDomain() . '_' . $name, $title, function () use ($name, $callback) {
            print $callback($name);
        }, $this->app->getDomain(), $section);
    }

    private function getInputField($name, $type, $classes = [], $attrs = [])
    {
        $d = $this->app->getDomain();
        $class = implode(' ', $classes);
        $value = $this->options->getValue($name);

        $attrs = array_merge([
            'type' => $type,
            'class' => $class,
            'value' => $value,
            'id' => $d . '_' . $name,
            'name' => $d . '[' . $name . ']',
        ], $attrs);

        $attrs = array_map(function ($key, $value) {
            return $key . '="' . $value . '"';
        }, array_keys($attrs), $attrs);

        $attrs = implode(' ', $attrs);

        $attrs = !empty($attrs) ? ' ' . $attrs : '';

        return "<input$attrs />";
    }

    private function getSelectField($name, $options = [], $attrs =  [])
    {
        $d = $this->app->getDomain();
        $current = $this->options->getValue($name);

        $attrs = array_merge([
            'id' => $d . '_' . $name,
            'name' => $d . '[' . $name . ']',
        ], $attrs);
        $attrs = array_map(function ($key, $value) {
            return $key . '="' . $value . '"';
        }, array_keys($attrs), $attrs);
        $attrs = implode(' ', $attrs);
        $attrs = !empty($attrs) ? ' ' . $attrs : '';

        $options = array_merge([
            '' => __('Please select...', $d),
        ], $options);
        $options = array_map(function ($key, $value) use ($current) {
            $selected = $key === $current;
            $selected = $selected ? ' selected' : '';

            return '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
        }, array_keys($options), $options);
        $options = implode('', $options);

        return "<select$attrs>$options</select>";
    }
}

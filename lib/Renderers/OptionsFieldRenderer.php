<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class OptionsFieldRenderer extends FieldRenderer
{
    const ATTR_OPTIONS = 'options';

    protected $options;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $attrs = is_array($attrs) ? $attrs : [];

        $this->options = Utilities::getArrayValueByKey($attrs, static::ATTR_OPTIONS, []);
    }

    public function render($value)
    {
        $options = $this->renderOptions($this->escape($value));

        return $this->renderWrapper($options);
    }

    protected function renderWrapper($options)
    {
        return "<ul>$options</ul>";
    }

    protected function renderOptions($current)
    {
        return implode('', array_map(function ($value, $label) use ($current) {
            $attrs =  Utilities::arrayToHtmlAttributes($this->getOptionAttributes($value, $current));

            return $this->renderOption($label, $attrs);
        }, array_keys($this->options), $this->options));
    }

    protected function getOptionAttributes($value, $current)
    {
        $active = $value === $current ? 'active' : null;

        return [
            'value' => $value,
            'class' => $active
        ];
    }

    protected function renderOption($label, $attrs)
    {
        return "<li$attrs>$label</li>";
    }

    protected function escape($value)
    {
        $value = parent::escape($value);

        return in_array($value, array_keys($this->options)) ? $value : null;
    }
}

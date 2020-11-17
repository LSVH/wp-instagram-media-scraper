<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

abstract class OptionsFieldRenderer extends FieldRenderer
{
    protected $options;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $attrs = is_array($attrs) ? $attrs : [];

        $this->options = Utilities::getArrayValueByKey($attrs, 'options', []);
    }

    public function render($value)
    {
        $options = $this->renderOptions($this->escape($value));

        return $this->renderWrapper($options);
    }

    protected abstract function renderWrapper($options);

    protected function renderOptions($current)
    {
        return implode('', array_map(function ($value, $label) use ($current) {
            $attrs =  Utilities::arrayToHtmlAttributes($this->getOptionAttributes($value, $current));

            return $this->renderOption($label, $attrs);
        }, array_keys($this->options), $this->options));
    }

    protected abstract function getOptionAttributes($value, $current);

    protected abstract function renderOption($label, $attrs);

    protected function escape($value)
    {
        $value = parent::escape($value);

        return in_array($value, array_keys($this->options)) ? $value : null;
    }
}

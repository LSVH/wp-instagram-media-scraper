<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class SelectFieldRenderer extends OptionsFieldRenderer
{
    protected function renderWrapper($options)
    {
        $attrs =  Utilities::arrayToHtmlAttributes($this->getAttributes());

        $placeholder = '<option value="">-</option>';

        return "<select$attrs>$placeholder.$options</select>";
    }

    protected function renderOption($label, $attrs)
    {
        return "<option$attrs>$label</option>";
    }

    protected function getOptionAttributes($value, $current)
    {
        return [
            'value' => $value,
            'selected' => $value === $current
        ];
    }
}

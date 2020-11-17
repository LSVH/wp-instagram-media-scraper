<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class CheckboxFieldRenderer extends OptionsFieldRenderer
{
    private $tag;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $this->tag = Utilities::getArrayValueByKey($attrs, 'tag', 'span');
        $this->name = count($this->options) > 1 && !empty($this->name)
            ? $this->name . '[]' : $this->name;
    }

    protected function renderWrapper($options)
    {
        $attrs = Utilities::arrayToHtmlAttributes([
            'class' => $this->class,
        ]);

        $tag = $this->tag;

        return "<$tag$attrs>$options</$tag>";
    }

    protected function renderOption($label, $attrs)
    {
        return "<label><input$attrs> $label</label>";
    }

    protected function getOptionAttributes($value, $current)
    {
        return array_merge([
            'type' => 'checkbox',
            'name' => $this->name,
            'value' => $value,
            'checked' => $value === $current,
        ]);
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class FieldRenderer implements Renderer
{
    protected $id;
    protected $name;
    protected $class;
    protected $escaper;

    public function __construct($attrs = [])
    {
        $attrs = is_array($attrs) ? $attrs : [];

        $this->class = Utilities::getArrayValueByKey($attrs, 'class');
        $this->class = is_array($this->class) ? implode(' ', $this->class) : $this->class;
        $this->escaper = Utilities::getArrayValueByKey($attrs, 'escaper');
        $this->id = Utilities::getArrayValueByKey($attrs, 'id');
        $this->name = Utilities::getArrayValueByKey($attrs, 'name');
    }

    public function render($value)
    {
        $attrs = Utilities::arrayToHtmlAttributes(array_merge($this->getAttributes(), [
            'value' => $this->escape($value),
        ]));

        return "<input$attrs/>";
    }

    protected function getAttributes()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'class' => $this->class,
        ];
    }

    protected function escape($value)
    {
        $fn = $this->escaper;
        if (is_callable($fn)) {
            return $fn($value);
        }

        return $value;
    }
}

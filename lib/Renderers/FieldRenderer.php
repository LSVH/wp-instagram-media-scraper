<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class FieldRenderer extends AbstractRenderer
{
    const ATTR_NAME = 'name';
    const ATTR_TYPE = 'type';
    const ATTR_VALUE = 'value';

    protected $name;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $attrs = is_array($attrs) ? $attrs : [];
        $this->name = Utilities::getArrayValueByKey($attrs, static::ATTR_NAME);
    }

    public function render($value)
    {
        $attrs = Utilities::arrayToHtmlAttributes(array_merge($this->getAttributes(), [
            static::ATTR_VALUE => $this->escape($value),
        ]));

        return "<input$attrs/>";
    }

    protected function getAttributes()
    {
        return [
            static::ATTR_ID => $this->id,
            static::ATTR_NAME => $this->name,
            static::ATTR_CLASS => $this->class,
        ];
    }

    protected function getOverriddenAttributes()
    {
        return array_merge(parent::getOverriddenAttributes(), [static::ATTR_NAME]);
    }
}

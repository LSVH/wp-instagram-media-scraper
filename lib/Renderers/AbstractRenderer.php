<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

abstract class AbstractRenderer implements Renderer
{
    const ATTR_ID = 'id';
    const ATTR_CLASS = 'class';
    const ATTR_ESCAPER = 'escaper';

    protected $id;
    protected $class;
    protected $escaper;

    public function __construct($attrs = [])
    {
        $attrs = is_array($attrs) ? $attrs : [];

        $this->id = Utilities::getArrayValueByKey($attrs, static::ATTR_ID);
        $this->class = Utilities::getArrayValueByKey($attrs, static::ATTR_CLASS);
        $this->class = is_array($this->class) ? implode(' ', $this->class) : $this->class;
        $this->escaper = Utilities::getArrayValueByKey($attrs, static::ATTR_ESCAPER);
    }

    public function render($value)
    {
        return $this->escape($value);
    }

    public function override($attrs = [])
    {
        $overriddenAttrs = $this->getOverriddenAttributes();
        foreach ($overriddenAttrs as $key) {
            if (property_exists($this, $key)) {
                $this->$key = Utilities::getArrayValueByKey($attrs, $key, $this->$key);
            }
        }
    }

    protected function getOverriddenAttributes()
    {
        return [static::ATTR_ID];
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

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class TextRenderer implements Renderer
{
    protected $escaper;

    public function __construct($attrs = [])
    {
        $attrs = is_array($attrs) ? $attrs : [];

        $this->escaper = Utilities::getArrayValueByKey($attrs, 'escaper');
    }

    public function render($value)
    {
        return $this->escape($value);
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

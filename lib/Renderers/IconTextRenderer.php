<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class IconTextRenderer extends TextRenderer
{
    protected $icon;
    protected $font;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $this->icon = Utilities::getArrayValueByKey($attrs, 'icon');
        $this->font = Utilities::getArrayValueByKey($attrs, 'font', 'dashicons');
    }

    public function render($value)
    {
        $content = parent::render($value);
        $iconClass = Utilities::prefix($this->font, $this->icon, '-');
        $class = empty($this->icon) ? null : $this->font . ' ' . $iconClass;

        $attrs = Utilities::arrayToHtmlAttributes([
            'class' => $class,
        ]);

        return "<span$attrs></span> $content";
    }
}

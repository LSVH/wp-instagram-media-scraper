<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\RendererFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\IconTextRenderer;

abstract class AbstractSection implements Section
{
    protected $domain;
    protected $options;

    public function __construct($domain, $options = [])
    {
        $this->domain = $domain;
        $this->options = $options;
    }

    public static function getName()
    {
        return ucfirst(preg_replace('/[-_]/', ' ', static::getId()));
    }

    public static function getIconName()
    {
        return static::getId();
    }

    public static function getTitle()
    {
        $renderer = RendererFactory::createInstance(IconTextRenderer::class, [
            'icon' => static::getIconName(),
        ]);

        return $renderer->render(static::getName());
    }

    public function getValue($name, $default = null)
    {
        $section = Utilities::getArrayValueByKey($this->options, static::getId(), []);

        return Utilities::getArrayValueByKey($section, $name, $default);
    }

    public function setValue($name, $value)
    {
        $this->options[static::getId()][$name] = $value;
        update_option($this->domain, $this->options);
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\Renderer;

class RendererFactory extends AbstractFactory
{
    public static function getInterface()
    {
        return Renderer::class;
    }
}

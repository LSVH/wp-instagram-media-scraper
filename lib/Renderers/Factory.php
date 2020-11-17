<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

abstract class Factory
{
    public static function createInstance($class, $attrs = [])
    {
        if (!class_exists($class) || !in_array(Renderer::class, class_implements($class))) {
            return null;
        }

        return new $class($attrs);
    }
}

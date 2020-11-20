<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

abstract class AbstractFactory implements Factory
{
    const ATTR_FQDN = 'fqdn';
    const ATTR_ARGS = 'args';

    public static function constructInstance($fqdn, $args = [])
    {
        return new $fqdn($args);
    }

    public static function createInstance($fqdn, $args = [])
    {
        if (!class_exists($fqdn) || !in_array(static::getInterface(), class_implements($fqdn))) {
            return null;
        }

        return static::constructInstance($fqdn, $args);
    }

    public static function createInstances($items)
    {
        return array_map(function ($item) {
            return static::createInstance(
                Utilities::getArrayValueByKey($item, static::ATTR_FQDN),
                Utilities::getArrayValueByKey($item, static::ATTR_ARGS)
            );
        }, $items);
    }
}

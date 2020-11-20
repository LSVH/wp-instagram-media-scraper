<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\Section;

class SectionFactory extends AbstractFactory
{
    const ATTR_DOMAIN = 'domain';

    public static function getInterface()
    {
        return Section::class;
    }

    public static function constructInstance($fqdn, $args = [])
    {
        $domain = Utilities::getArrayValueByKey($args, static::ATTR_DOMAIN);
        $args = Utilities::getArrayValueByKey($args, static::ATTR_ARGS, []);

        return new $fqdn($domain, $args);
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers\Scraper;

class ScraperFactory extends AbstractFactory
{
    const ATTR_DOMAIN = 'domain';
    const ATTR_STATS = 'stats';
    const ATTR_DATA = 'data';

    public static function getInterface()
    {
        return Scraper::class;
    }

    public static function constructInstance($fqdn, $args = [])
    {
        $domain = Utilities::getArrayValueByKey($args, static::ATTR_DOMAIN);
        $stats = Utilities::getArrayValueByKey($args, static::ATTR_STATS);
        $data = Utilities::getArrayValueByKey($args, static::ATTR_DATA);

        return new $fqdn($domain, $stats, $data);
    }
}

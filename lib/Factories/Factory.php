<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

interface Factory
{
    public static function getInterface();

    public static function constructInstance($fqdn, $args = []);

    public static function createInstance($fqdn, $args = []);

    public static function createInstances($items);
}

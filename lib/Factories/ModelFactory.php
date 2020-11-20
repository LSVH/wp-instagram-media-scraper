<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Models\Model;

class ModelFactory extends AbstractFactory
{
    public static function getInterface()
    {
        return Model::class;
    }
}

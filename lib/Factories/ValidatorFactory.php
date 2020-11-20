<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Factories;

use LSVH\WordPress\Plugin\SocialMediaScraper\Validators\Validator;

class ValidatorFactory extends AbstractFactory
{
    public static function getInterface()
    {
        return Validator::class;
    }
}

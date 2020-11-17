<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

interface Validator
{
    public function __construct($value);
    public function validate($input);
}

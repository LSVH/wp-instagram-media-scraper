<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

interface Validator
{
    public function __construct($args = []);
    public function isValid($input);
    public function getId();
    public function getErrorCode();
    public function getErrorMessage();
    public function override($attrs = []);
}

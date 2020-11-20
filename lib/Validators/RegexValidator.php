<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class RegexValidator extends AbstractValidator
{
    const ATTR_PATTERN = 'pattern';

    private $pattern;

    public function __construct($args = [])
    {
        parent::__construct($args);

        $this->pattern = Utilities::getArrayValueByKey($args, static::ATTR_PATTERN);
    }

    public function isValid($input)
    {
        return boolval(preg_match($this->pattern, $input));
    }

    public function getErrorMessage()
    {
        return sprintf('The input for "%s" does not match the pattern: %s', $this->id, $this->pattern);
    }
}

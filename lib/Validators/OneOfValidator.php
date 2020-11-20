<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class OneOfValidator extends AbstractValidator
{
    const ATTR_VALUES = 'values';

    private $values;

    public function __construct($args = [])
    {
        parent::__construct($args);

        $this->values = Utilities::getArrayValueByKey($args, static::ATTR_VALUES);
    }

    public function isValid($input)
    {
        return in_array($input, $this->values);
    }

    public function getErrorMessage()
    {
        return sprintf('The input for "%s" is not one of the allowed values: %s', $this->id, $this->getReadableValues());
    }

    private function getReadableValues()
    {
        return implode(', ', $this->values);
    }
}

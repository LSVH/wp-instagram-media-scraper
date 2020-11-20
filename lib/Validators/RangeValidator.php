<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class RangeValidator extends AbstractValidator
{
    const ATTR_RANGES = 'ranges';

    private $ranges;

    public function __construct($args = [])
    {
        parent::__construct($args);

        $this->ranges = Utilities::getArrayValueByKey($args, static::ATTR_RANGES);
    }

    public function isValid($input)
    {
        if (!is_numeric($input)) {
            return false;
        }

        return !empty(array_filter(array_map(function ($range) use ($input) {
            $lowest = Utilities::getArrayValueByKey($range, 0);
            $highest = Utilities::getArrayValueByKey($range, 1);

            $between = !empty($lowest) && !empty($highest) && ($input >= $lowest && $input <= $highest);
            $above = !empty($lowest) && empty($highest) && $input >= $lowest;
            $below = empty($lowest) && !empty($highest) && $input <= $highest;

            return $between || $above || $below;
        }, $this->ranges)));
    }

    public function getErrorMessage()
    {
        return sprintf('The input for "%s" is not within one of the following ranges: %s', $this->id, $this->getReadableRanges());
    }

    private function getReadableRanges()
    {
        return implode(', ', array_filter(array_map(function ($range) {
            return is_array($range) ? implode(' - ', $range) : null;
        }, $this->ranges)));
    }
}

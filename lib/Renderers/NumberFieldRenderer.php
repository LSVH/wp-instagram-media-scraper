<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class NumberFieldRenderer extends FieldRenderer
{
    const ATTR_MIN = 'min';
    const ATTR_MAX = 'max';
    const ATTR_STEP = 'step';

    private $min;
    private $max;
    private $step;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $attrs = is_array($attrs) ? $attrs : [];

        $this->min = Utilities::getArrayValueByKey($attrs, static::ATTR_MIN);
        $this->max = Utilities::getArrayValueByKey($attrs, static::ATTR_MAX);
        $this->step = Utilities::getArrayValueByKey($attrs, static::ATTR_STEP);
    }

    protected function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            static::ATTR_TYPE => 'number',
            static::ATTR_MIN => $this->min,
            static::ATTR_MAX => $this->max,
            static::ATTR_STEP => $this->step,
        ]);
    }

    protected function escape($value)
    {
        $value = parent::escape($value);

        return is_numeric($value) ? $value : null;
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class NumberFieldRenderer extends FieldRenderer
{
    private $min;
    private $max;
    private $step;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $attrs = is_array($attrs) ? $attrs : [];

        $this->min = Utilities::getArrayValueByKey($attrs, 'min');
        $this->max = Utilities::getArrayValueByKey($attrs, 'max');
        $this->step = Utilities::getArrayValueByKey($attrs, 'step');
    }

    protected function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            'type' => 'number',
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
        ]);
    }

    protected function escape($value)
    {
        $value = parent::escape($value);

        return is_numeric($value) ? $value : null;
    }
}

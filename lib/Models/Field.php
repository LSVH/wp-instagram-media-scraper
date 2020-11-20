<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Models;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class Field extends AbstractModel
{
    const ATTR_LABEL = 'label';
    const ATTR_RENDERER = 'renderer';
    const ATTR_VALIDATOR = 'validator';

    private $label;
    private $renderer;
    private $validator;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $this->label = Utilities::getArrayValueByKey($attrs, static::ATTR_LABEL);
        $this->renderer = Utilities::getArrayValueByKey($attrs, static::ATTR_RENDERER);
        $this->validator = Utilities::getArrayValueByKey($attrs, static::ATTR_VALIDATOR);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    public function getValidator()
    {
        return $this->validator;
    }
}

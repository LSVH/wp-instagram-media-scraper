<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Validators;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

abstract class AbstractValidator implements Validator
{
    const ATTR_ID = 'id';

    protected $id;

    public function __construct($args = [])
    {
        $this->id = Utilities::getArrayValueByKey($args, static::ATTR_ID);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getErrorCode()
    {
        return implode('@', [(new \ReflectionClass($this))->getShortName(), $this->getId()]);
    }

    public function override($attrs = [])
    {
        $overriddenAttrs = $this->getOverriddenAttributes();
        foreach ($overriddenAttrs as $key) {
            if (property_exists($this, $key)) {
                $this->$key = Utilities::getArrayValueByKey($attrs, $key, $this->$key);
            }
        }
    }

    protected function getOverriddenAttributes()
    {
        return [static::ATTR_ID];
    }
}

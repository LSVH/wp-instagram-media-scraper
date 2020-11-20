<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Models;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

abstract class AbstractModel implements Model
{
    const ATTR_ID = 'id';

    protected $id;

    public function __construct($attrs = [])
    {
        $this->id = Utilities::getArrayValueByKey($attrs, static::ATTR_ID);
    }

    public function getId()
    {
        return $this->id;
    }
}

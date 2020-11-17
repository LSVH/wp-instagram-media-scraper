<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

interface Section
{
    public function __construct($domain, $options = []);

    public function getFields();

    public function getValue($name, $default = null);

    public function setValue($name, $value);

    public static function getId();

    public static function getName();

    public static function getIconName();

    public static function getTitle();
}

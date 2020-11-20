<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Models;

interface Model
{
    public function __construct($attrs = []);

    public function getId();
}

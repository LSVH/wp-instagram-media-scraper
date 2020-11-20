<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

interface Installer
{
    public static function install($domain, $args = []);
}

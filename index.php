<?php

/**
 * Plugin Name: Social Media Scraper
 * Plugin URI: https://github.com/LSVH/wp-social-media-scraper
 * Description: Scrape media from a specified social media account.
 * Version: 1.0.1
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: LSVH
 * Author URI: https://lsvh.org/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: social-media-scraper
 * Domain Path: /languages
 */

$autoloader = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    die('Autoloader not found.');
}

require $autoloader;

use LSVH\WordPress\Plugin\SocialMediaScraper\Bootstrap;

$boot = new Bootstrap(__FILE__);
$boot->exec();

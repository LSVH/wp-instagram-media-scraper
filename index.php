<?php

/**
 * Plugin Name: Instagram Media Scraper
 * Plugin URI: https://wordpress.org/plugins/instagram-media-scraper/
 * Description: Scrape Media from a specified Instagram account.
 * Version: 1.0.0
 * Requires at least: 5.5
 * Requires PHP: 7.2
 * Author: LSVH
 * Author URI: https://lsvh.org/
 * License: GNU
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: ig-media-scraper
 * Domain Path: /languages
 */

$autoloader = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    die('Autoloader not found.');
}

require $autoloader;

use LSVH\WordPress\Plugin\InstagramMediaScraper\Bootstrap;

$boot = new Bootstrap(__FILE__);
$boot->exec();

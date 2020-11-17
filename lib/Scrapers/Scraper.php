<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers;

interface Scraper
{
    public function __construct($domain, $statistics, $settings);

    public function execute();

    public function getId();

    public function getInterval();
}

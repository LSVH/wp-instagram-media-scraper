<?php

namespace LSVH\WordPress\Plugin\InstagramMediaScraper;

class App
{
    private $title;
    private $domain;

    public function __construct($title, $domain)
    {
        $this->title = $title;
        $this->domain = $domain;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDomain()
    {
        return $this->domain;
    }
}

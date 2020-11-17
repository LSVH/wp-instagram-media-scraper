<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class ScraperItem
{
    private $slug;
    private $title;
    private $content;
    private $date;
    private $author;
    private $source;
    private $resource;

    public function __construct($attrs = [])
    {
        $this->slug = Utilities::getArrayValueByKey($attrs, 'slug');
        $this->title = Utilities::getArrayValueByKey($attrs, 'title');
        $this->content = Utilities::getArrayValueByKey($attrs, 'content');
        $this->date = Utilities::getArrayValueByKey($attrs, 'date');
        $this->author = Utilities::getArrayValueByKey($attrs, 'author');
        $this->source = Utilities::getArrayValueByKey($attrs, 'source');
        $this->resource = Utilities::getArrayValueByKey($attrs, 'resource');
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        if (is_numeric($this->date)) {
            return date('Y-m-d H:i:s', $this->date);
        }

        if (is_string($this->date)) {
            return $this->date;
        }

        return null;
    }

    public function getAuthor()
    {
        return is_numeric($this->author) ? $this->author : null;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getResource()
    {
        return $this->resource;
    }
}

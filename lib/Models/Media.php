<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Models;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class Media extends AbstractModel
{
    const ATTR_TITLE = 'title';
    const ATTR_CONTENT = 'content';
    const ATTR_DATE = 'date';
    const ATTR_AUTHOR = 'author';
    const ATTR_SOURCE = 'source';
    const ATTR_RESOURCE = 'resource';

    private $title;
    private $content;
    private $date;
    private $author;
    private $source;
    private $resource;

    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        $this->title = Utilities::getArrayValueByKey($attrs, static::ATTR_TITLE);
        $this->content = Utilities::getArrayValueByKey($attrs, static::ATTR_CONTENT);
        $this->date = Utilities::getArrayValueByKey($attrs, static::ATTR_DATE);
        $this->author = Utilities::getArrayValueByKey($attrs, static::ATTR_AUTHOR);
        $this->source = Utilities::getArrayValueByKey($attrs, static::ATTR_SOURCE);
        $this->resource = Utilities::getArrayValueByKey($attrs, static::ATTR_RESOURCE);
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

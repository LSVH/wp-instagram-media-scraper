<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\InstagramSection;
use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;

class InstagramScraper extends AbstractScraper
{
    public function getId()
    {
        return Utilities::prefix($this->settings->getId(), 'scraper');
    }

    public function getInterval()
    {
        return $this->settings->getValue(InstagramSection::FIELD_INTERVAL);
    }

    public function execute()
    {
        $username = $this->settings->getValue(InstagramSection::FIELD_USERNAME);
        $amount = $this->settings->getValue(InstagramSection::FIELD_AMOUNT, 10);
        $scraper = new \InstagramScraper\Instagram(new \GuzzleHttp\Client);

        $items = array_map(function ($media) {
            return $this->extractItemFromMedia($media);
        }, empty($username) ? [] : $scraper->getMedias($username, $amount));

        $this->createAttachments($items);
    }

    private function extractItemFromMedia($media)
    {
        $username = $this->settings->getValue(InstagramSection::FIELD_USERNAME);
        $prefix = Utilities::prefix($this->domain, $username);
        $resource = $media->getType() !== 'video'
            ? $media->getImageHighResolutionUrl() : $media->getVideoStandardResolutionUrl();

        return new ScraperItem([
            'slug' => Utilities::prefix($prefix, $media->getId()),
            'title' => Utilities::prefix(ucfirst($username), $media->getId(), ' '),
            'content' => $media->getCaption(),
            'date' => $media->getCreatedTime(),
            'author' => $this->getAuthor(),
            'source' => $media->getLink(),
            'resource' => $resource,
        ]);
    }
}

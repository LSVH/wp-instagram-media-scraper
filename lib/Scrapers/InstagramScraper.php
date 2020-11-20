<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Scrapers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Models\Media;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\ModelFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Sections\InstagramSection;

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

        $this->storeMedias($items);
    }

    private function extractItemFromMedia($media)
    {
        $username = $this->settings->getValue(InstagramSection::FIELD_USERNAME);
        $prefix = Utilities::prefix($this->domain, $username);
        $resource = $media->getType() !== 'video'
            ? $media->getImageHighResolutionUrl() : $media->getVideoStandardResolutionUrl();

        return ModelFactory::createInstance(Media::class, [
            Media::ATTR_ID => Utilities::prefix($prefix, $media->getId()),
            Media::ATTR_TITLE => Utilities::prefix(ucfirst($username), $media->getId(), ' '),
            Media::ATTR_CONTENT => $media->getCaption(),
            Media::ATTR_DATE => $media->getCreatedTime(),
            Media::ATTR_AUTHOR => $this->getAuthor(),
            Media::ATTR_SOURCE => $media->getLink(),
            Media::ATTR_RESOURCE => $resource,
        ]);
    }
}

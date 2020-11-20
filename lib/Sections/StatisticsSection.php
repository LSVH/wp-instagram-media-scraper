<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\ModelFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\RendererFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Models\Field;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\TextRenderer;

class StatisticsSection extends AbstractSection
{
    const FIELD_TOTAL = 'total-scraped';
    const FIELD_LAST = 'last-scraped';

    public static function getId()
    {
        return 'statistics';
    }

    public static function getIconName()
    {
        return 'chart-line';
    }

    public function getFields()
    {
        $domain = $this->domain;

        return [
            ModelFactory::createInstance(Field::class, [
                Field::ATTR_ID => static::FIELD_TOTAL,
                Field::ATTR_LABEL => __('Total media scraped', $domain),
                Field::ATTR_RENDERER => RendererFactory::createInstance(TextRenderer::class, [
                    'escaper' => function ($value) {
                        return $this->getNumber($value);
                    }
                ]),
            ]),
            ModelFactory::createInstance(Field::class, [
                Field::ATTR_ID => static::FIELD_LAST,
                Field::ATTR_LABEL => __('Last run on', $domain),
                Field::ATTR_RENDERER => RendererFactory::createInstance(TextRenderer::class, [
                    'escaper' => function ($value) {
                        return $this->getTimestamp($value);
                    }
                ]),
            ]),
        ];
    }

    private function getNumber($value, $default = 0)
    {
        return is_numeric($value) ? $value : $default;
    }

    private function getTimestamp($value)
    {
        return empty($value) || !is_numeric($value) ? '-' : implode(' ', [
            date(get_option('date_format'), $value),
            __('at', $this->domain),
            date(get_option('time_format'), $value)
        ]);
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

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
            [
                'id' => static::FIELD_TOTAL,
                'label' => __('Total media scraped', $domain),
                'renderer' => TextRenderer::class,
                'renderer_attrs' => [
                    'escaper' => function ($value) {
                        return is_numeric($value) ? $value : 0;
                    }
                ]
            ],
            [
                'id' => static::FIELD_LAST,
                'label' => __('Last run on', $domain),
                'renderer' => TextRenderer::class,
                'renderer_attrs' => [
                    'escaper' => function ($value) {
                        return $this->getTimestamp($value);
                    }
                ]
            ]
        ];
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

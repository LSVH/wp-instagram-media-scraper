<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\TextFieldRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\NumberFieldRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\SelectFieldRenderer;

class InstagramSection extends AbstractSection
{
    const FIELD_USERNAME = 'username';
    const FIELD_AMOUNT = 'amount';
    const FIELD_INTERVAL = 'interval';

    public static function getId()
    {
        return 'instagram';
    }

    public function getFields()
    {
        $domain = $this->domain;

        return [
            [
                'id' => static::FIELD_USERNAME,
                'label' => __('Username', $domain),
                'renderer' => TextFieldRenderer::class,
                'renderer_attrs' => [
                    'class' => ['regular-text', 'code'],
                ],
            ],
            [
                'id' => static::FIELD_AMOUNT,
                'label' => __('Amount', $domain),
                'renderer' => NumberFieldRenderer::class,
                'renderer_attrs' => [
                    'min' => 1,
                    'max' => 50,
                    'class' => 'small-text'
                ],
            ],
            [
                'id' => static::FIELD_INTERVAL,
                'label' => __('Interval', $domain),
                'renderer' => SelectFieldRenderer::class,
                'renderer_attrs' => [
                    'options' => [
                        'hourly' => __('Hourly', $domain),
                        'twicedaily' => __('Twice daily', $domain),
                        'daily' => __('Daily', $domain),
                        'weekly' => __('Weekly', $domain),
                    ]
                ]
            ]
        ];
    }
}

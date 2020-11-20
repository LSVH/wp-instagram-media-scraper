<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Sections;

use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\ModelFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\RendererFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Factories\ValidatorFactory;
use LSVH\WordPress\Plugin\SocialMediaScraper\Models\Field;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\TextFieldRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\NumberFieldRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\SelectFieldRenderer;
use LSVH\WordPress\Plugin\SocialMediaScraper\Validators\RegexValidator;
use LSVH\WordPress\Plugin\SocialMediaScraper\Validators\RangeValidator;
use LSVH\WordPress\Plugin\SocialMediaScraper\Validators\OneOfValidator;

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
            ModelFactory::createInstance(Field::class, [
                Field::ATTR_ID => static::FIELD_USERNAME,
                Field::ATTR_LABEL => __('Username', $domain),
                Field::ATTR_RENDERER => RendererFactory::createInstance(TextFieldRenderer::class, [
                    TextFieldRenderer::ATTR_CLASS => ['regular-text', 'code'],
                ]),
                Field::ATTR_VALIDATOR => ValidatorFactory::createInstance(RegexValidator::class, [
                    RegexValidator::ATTR_PATTERN => '/^[\w](?!.*?\.{2})[\w.]{1,28}[\w]$/',
                ]),
            ]),
            ModelFactory::createInstance(Field::class, [
                Field::ATTR_ID => static::FIELD_AMOUNT,
                Field::ATTR_LABEL => __('Amount', $domain),
                Field::ATTR_RENDERER => RendererFactory::createInstance(NumberFieldRenderer::class, [
                    NumberFieldRenderer::ATTR_MIN => 1,
                    NumberFieldRenderer::ATTR_MAX => 50,
                    NumberFieldRenderer::ATTR_STEP => 1,
                    NumberFieldRenderer::ATTR_CLASS => 'small-text',
                ]),
                Field::ATTR_VALIDATOR => ValidatorFactory::createInstance(RangeValidator::class, [
                    RangeValidator::ATTR_RANGES => [[1, 50]],
                ]),
            ]),
            ModelFactory::createInstance(Field::class, [
                Field::ATTR_ID => static::FIELD_INTERVAL,
                Field::ATTR_LABEL => __('Interval', $domain),
                Field::ATTR_RENDERER => RendererFactory::createInstance(SelectFieldRenderer::class, [
                    SelectFieldRenderer::ATTR_OPTIONS => [
                        'hourly' => __('Hourly', $domain),
                        'twicedaily' => __('Twice daily', $domain),
                        'daily' => __('Daily', $domain),
                        'weekly' => __('Weekly', $domain),
                    ],
                ]),
                Field::ATTR_VALIDATOR => ValidatorFactory::createInstance(OneOfValidator::class, [
                    OneOfValidator::ATTR_VALUES => ['hourly', 'twicedaily', 'daily', 'weekly'],
                ]),
            ]),
        ];
    }
}

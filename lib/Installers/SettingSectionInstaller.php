<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\Factory;

class SettingSectionInstaller implements Installer
{
    public static function install($domain, $options = [])
    {
        if (!empty(array_filter($options))) {
            static::addSections($domain, $options);
        }
    }

    private static function addSections($domain, $sections)
    {
        foreach ($sections as $section) {
            add_settings_section($section->getId(), $section->getTitle(), null, $domain);

            static::addSectionFields($domain, $section);
        }
    }

    private static function addSectionFields($domain, $section)
    {
        $sectionId = $section->getId();
        $fields = $section->getFields();
        $idPrefix = Utilities::prefix($domain, $sectionId);
        $namePrefix = Utilities::prefix($domain, $sectionId, '[', ']');

        foreach ($fields as $field) {
            $id = Utilities::getArrayValueByKey($field, 'id');
            $label = Utilities::getArrayValueByKey($field, 'label');
            $renderer = Utilities::getArrayValueByKey($field, 'renderer');
            $staticAttrs = Utilities::getArrayValueByKey($field, 'renderer_attrs', []);
            $dynamicAttrs = [
                'id' => Utilities::prefix($idPrefix, $id),
                'name' => Utilities::prefix($namePrefix, $id, '[', ']'),
            ];
            $attrs = array_merge($staticAttrs, $dynamicAttrs);
            $value = $section->getValue($id);

            add_settings_field($id, $label, function () use ($renderer, $attrs, $value) {
                $instance = Factory::createInstance($renderer, $attrs);
                print $instance->render($value);
            }, $domain, $sectionId);
        }
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Utilities;
use LSVH\WordPress\Plugin\SocialMediaScraper\Renderers\FieldRenderer;

class SettingSectionInstaller implements Installer
{
    public static function install($domain, $args = [])
    {
        if (!empty(array_filter($args))) {
            static::addSections($domain, $args);
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
            $id = $field->getId();
            $label = $field->getLabel();
            $renderer = $field->getRenderer();
            if (!empty($renderer)) {
                $value = $section->getValue($id);
                $renderer->override([
                    FieldRenderer::ATTR_ID => Utilities::prefix($idPrefix, $id),
                    FieldRenderer::ATTR_NAME => Utilities::prefix($namePrefix, $id, '[', ']'),
                ]);

                add_settings_field($id, $label, function () use ($renderer, $value) {
                    print $renderer->render($value);
                }, $domain, $sectionId);
            }
        }
    }
}

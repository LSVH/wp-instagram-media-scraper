<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Installers;

use LSVH\WordPress\Plugin\SocialMediaScraper\Validators\AbstractValidator;

class SettingValidatorInstaller implements Installer
{
    public static function install($domain, $args = [])
    {
        add_filter("validate_setting_$domain", function ($setting) use ($domain, $args) {
            if (is_array($setting)) {
                foreach ($setting as $sectionId => $fields) {
                    $section = static::getSectionById($args, $sectionId);
                    if (is_array($fields) && !empty($section)) {
                        $validators = static::getValidators($section);
                        foreach ($fields as $fieldId => $value) {
                            $validator = static::getValidatorById($validators, $fieldId);
                            if (!empty($value) && $validator && !$validator->isValid($value)) {
                                static::addDefaultErrorMessage($domain, $validator->getErrorCode(), $validator->getErrorMessage());
                                unset($setting[$sectionId][$fieldId]);
                            }
                        }
                    }
                }
            }
            return $setting;
        });
    }

    private static function getValidators($section)
    {
        return array_filter(array_map(function ($field) {
            $id = $field->getId();

            $validator = $field->getValidator();
            if (!empty($validator)) {
                $validator->override([
                    AbstractValidator::ATTR_ID => $id,
                ]);
            }

            return $validator;
        }, $section->getFields()));
    }

    private static function getValidatorById($validators, $id)
    {
        return current(array_filter($validators, function ($validator) use ($id) {
            return $validator->getId() === $id;
        }));
    }

    private static function getSectionById($sections, $id)
    {
        return current(array_filter($sections, function ($section) use ($id) {
            return $section->getId() === $id;
        }));
    }

    private static function addDefaultErrorMessage($domain, $code, $msg)
    {
        $message = sprintf(__('An error occurred while validating your submission: %s', $domain), $msg);
        add_settings_error($domain, $code, $message);
    }
}

<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper;

abstract class Utilities
{
    public static function getArrayValueByKey($array, $key, $default = null)
    {
        return is_array($array) && array_key_exists($key, $array) ? $array[$key] : $default;
    }

    public static function arrayToHtmlAttributes($array)
    {
        $array = array_filter($array);
        $attrs = implode(' ', array_map(function ($key, $value) {
            return $key . '="' . $value . '"';
        }, array_keys($array), $array));

        return !empty($attrs) ? ' ' . $attrs : '';
    }

    public static function prefix($id, $subject, $separator = '_', $wrap = '')
    {
        if (!empty($id) && is_string($id)) {
            return $id . $separator . $subject . $wrap;
        }

        return $subject;
    }
}

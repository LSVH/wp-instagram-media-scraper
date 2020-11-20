<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

class TextFieldRenderer extends FieldRenderer
{
    protected function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            static::ATTR_TYPE => 'text',
        ]);
    }

    protected function escape($value)
    {
        $value = parent::escape($value);

        return is_string($value) ? $value : null;
    }
}

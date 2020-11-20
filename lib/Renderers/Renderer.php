<?php

namespace LSVH\WordPress\Plugin\SocialMediaScraper\Renderers;

interface Renderer
{
    public function __construct($attrs = []);
    public function override($attrs = []);
    public function render($value);
}

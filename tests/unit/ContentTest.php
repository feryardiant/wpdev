<?php

namespace Tests;

use Blank\Content;

class ContentTest extends TestCase {
    public function test_get_uri() {
        $instance = new Content($this->create_theme_instance_without_features());

        $this->assertInstanceOf(Content::class, $instance);
    }
}

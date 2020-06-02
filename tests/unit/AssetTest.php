<?php

namespace Tests;

use Blank\Asset;
use WP_Mock as mock;

class AssetTest extends TestCase {
    public function test_get_uri() {
        $asset = new Asset($this->create_theme_instance_without_features());

        $this->assertEquals('/assets/js/main.min.js', $asset->get_uri('main.js'));
    }
}

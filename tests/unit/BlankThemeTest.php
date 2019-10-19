<?php

namespace Tests;

use Blank\Theme;
use ReflectionClass;
use WP_Mock as mock;

class BlankThemeTest extends TestCase {
    protected $transient = [
        'siteurl'    => 'http://localhost',
        'name'       => 'Blank',
        'slug'       => 'blank',
        'child_slug' => 'blank',
        'theme_uri'  => 'http://example.com',
        'version'    => '0.2.0',
        'author'     => 'Fery Wardiyanto',
        'author_uri' => 'http://example.com',
    ];

    protected function new_instance_without_constructor(string $class_name) {
        $reflector = new ReflectionClass($class_name);

        return $reflector->newInstanceWithoutConstructor();
    }

    public function test_constructor() {
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->assertInstanceOf(Theme::class, $theme);
    }
}

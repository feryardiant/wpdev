<?php

namespace Tests;

use Blank\Feature;
use Blank\Theme;
use Closure;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
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

    protected function new_instance_without_constructor(string $class_name, Closure $callback = null) {
        $reflector = new ReflectionClass($class_name);

        $this->transient['parent_dir'] = BASE_PATH . '/source/themes/blank';
        $this->transient['parent_uri'] = $this->transient['siteurl'] . '/wp-content/themes/blank';
        $this->transient['child_dir']  = $this->transient['parent_dir'];
        $this->transient['child_uri']  = $this->transient['parent_uri'];

        if ($callback) {
            $callback($reflector);
        }

        return $reflector->newInstanceWithoutConstructor();
    }

    public function test_constructor() {
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->assertInstanceOf(Theme::class, $theme);
    }

    public function test_transient_name() {
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);
            $prop->setValue($theme, (object) []);
        });

        $this->assertEquals('blank_theme_info', $theme->transient_name('theme_info'));
    }

    public function test_setter_and_getter() {
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);
            $prop->setValue($theme, (object) [
                'theme_info' => $this->transient,
            ]);
        });
        
        $this->assertNull($theme->dummy);

        $theme->dummy = DummyFeature::class;

        $this->assertInstanceOf(DummyFeature::class, $theme->dummy);

        $this->assertEquals($this->transient['siteurl'], $theme->siteurl);
    }

    public function test_setter_error_on_non_feature_subclass() {
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->expectException(InvalidArgumentException::class);

        $theme->foo = new class {};
    }

    public function test_setter_error_on_non_feature_instance() {
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->expectException(InvalidArgumentException::class);

        $theme->foo = function () {
            return new class {};
        };
    }
}

class DummyFeature extends Feature {
    public function initialize() : void {
        // .
    }
}

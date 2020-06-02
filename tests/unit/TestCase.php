<?php

namespace Tests;

use Closure;
use ReflectionClass;
use WP_Mock as mock;
use WP_Mock\Tools\TestCase as WP_Mock_TestCase;

class TestCase extends WP_Mock_TestCase {
    protected $transient = [
        'siteurl'    => 'http://localhost',
        'name'       => 'ThemeName',
        'slug'       => 'slug',
        'child_slug' => 'slug',
        'theme_uri'  => 'http://example.com',
        'version'    => '0.2.0',
        'author'     => 'John Doe',
        'author_uri' => 'http://example.com',
    ];

    protected function new_instance_without_constructor(string $class_name, Closure $callback = null) {
        $reflector = new ReflectionClass($class_name);

        $this->transient['parent_dir'] = STUBS_PATH;
        $this->transient['parent_uri'] = $this->transient['siteurl'] . '/wp-content/themes/slug';
        $this->transient['child_dir']  = $this->transient['parent_dir'];
        $this->transient['child_uri']  = $this->transient['parent_uri'];

        if ($callback) {
            $callback($reflector);
        }

        return $reflector->newInstanceWithoutConstructor();
    }

    protected function create_theme_instance() {
        mock::userFunction('get_transient', [
            'times' => 1,
            'return' => $this->transient,
        ]);

        mock::userFunction('trailingslashit', [
            'times' => 4,
            'return' => '/',
        ]);

        mock::userFunction('get_template_directory', [
            'times' => 1,
            'return' => $dir = STUBS_PATH,
        ]);

        mock::userFunction('get_stylesheet_directory', [
            'times' => 1,
            'return' => $uri = $this->transient['siteurl'] . '/wp-content/themes/slug',
        ]);

        mock::userFunction('get_template_directory_uri', [
            'times' => 1,
            'return' => $dir,
        ]);

        mock::userFunction('get_stylesheet_directory_uri', [
            'times' => 1,
            'return' => $uri,
        ]);

        return new \Blank\Theme();
    }

    protected function create_theme_instance_without_features() {
        add_filter( 'blank_init', function () {
            return [];
        }, 10, 0 );

        mock::userFunction('add_action', []);

        return $this->create_theme_instance();
    }
}

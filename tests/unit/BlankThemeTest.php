<?php

namespace Tests;

use Blank\Theme;
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
        'parent_dir' => '',
        'child_dir'  => '',
        'parent_uri' => '',
        'child_uri'  => '',
    ];

    public function test_constructor() {
        mock::userFunction('get_transient', [
            'args'   => 'blank_theme_info',
            'times'  => 1,
            'return' => $this->transient,
        ]);

        $theme = new Theme;

        $this->assertInstanceOf(Theme::class, $theme);
    }
}

<?php

namespace Tests;

use Blank\Asset;
use Blank\Comment;
use Blank\Content;
use Blank\Customizer;
use Blank\Feature;
use Blank\Menu;
use Blank\Template;
use Blank\Theme;
use Blank\Widgets;
use Closure;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;
use WP_Mock as mock;

class ThemeTest extends TestCase {
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

    public function test_constructor() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->assertInstanceOf(Theme::class, $theme);
    }

    public function test_transient_name() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);
            $prop->setValue($theme, (object) []);
        });

        $this->assertEquals('blank_theme_info', $theme->transient_name('theme_info'));
    }

    public function test_get_instance_feature_class() {
        $this->expectException(RuntimeException::class);

        DummyFeature::get_instance();
    }

    public function test_setter_and_getter() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);
            $prop->setValue($theme, (object) [
                'info' => $this->transient,
            ]);
        });

        $this->assertNull($theme->dummy);

        $theme->dummy = DummyFeature::class;

        $this->assertInstanceOf(DummyFeature::class, $theme->dummy);
        $this->assertInstanceOf(DummyFeature::class, DummyFeature::get_instance());

        $this->assertEquals($this->transient['siteurl'], $theme->siteurl);
    }

    public function test_setter_error_on_non_feature_subclass() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->expectException(InvalidArgumentException::class);

        $theme->foo = new class {};
    }

    public function test_setter_error_on_non_feature_instance() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class);

        $this->expectException(InvalidArgumentException::class);

        $self = $this;
        $theme->foo = function ($theme) use ($self) {
            $self->assertInstanceOf(Theme::class, $theme);
            return new class {};
        };
    }

    public function test_option() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);
            $prop->setValue($theme, (object) [
                'info' => [ 'slug' => 'blank' ]
            ]);
        });

        mock::userFunction('wp_parse_args', [
            'times'  => 9,
            'return' => [
                'default' => null,
                'title' => 'text',
                'container' => '',
            ],
        ]);

        $theme->add_option('general', [
            'title'    => 'Panel with 1 section & 1 setting',
            'sections' => [
                'layout' => [
                    'title'       => 'Layout',
                    'settings'    => [
                        'container' => [
                            'label'   => 'Global Container',
                            'type'    => 'radio',
                            'default' => '',
                            'choices' => [
                                'wide'     => 'Wide',
                                'boxed'    => 'Boxed',
                                'fluid'    => 'Fluid',
                                'narrowed' => 'Narrowed',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $options = $theme->options();

        $this->assertCount(1, $options['panels']);
        $this->assertCount(1, $options['sections']);
        $this->assertCount(1, $options['settings']);
        $this->assertCount(1, $options['values']);
        $this->assertEquals('', $options['values']['container']);

        $theme->add_option('foo', [
            'title'    => 'Add one Panel',
            'sections' => [],
        ]);

        $this->assertCount(2, $theme->options('panels'));

        $theme->add_option('bar', [
            'title'    => 'Add one section',
            'panel'    => 'general',
            'settings' => [],
        ]);

        $this->assertCount(2, $theme->options('sections'));

        $theme->add_options([
            '3rd_panel' => [
                'title'    => 'Third Panel',
                'sections' => [],
            ],
            '4th_panel' => [
                'title'    => 'Fourth Panel',
                'sections' => [],
            ],
            '5th_panel' => [
                'title'    => 'Fifth Panel',
                'sections' => [],
            ],
        ]);

        $this->assertCount(5, $theme->options('panels'));

        mock::userFunction('get_theme_mods', [
            'times'  => 1,
            'return' => [],
        ]);

        $this->assertEquals('', $theme->get_option('container'));

        $this->expectException(InvalidArgumentException::class);

        $theme->get_option('foo_bar');
    }

    public function test_theme_features() {
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

        $theme = new Theme();

        $this->assertInstanceOf(Asset::class, $theme->asset);
        $this->assertInstanceOf(Comment::class, $theme->comment);
        $this->assertInstanceOf(Content::class, $theme->content);
        $this->assertInstanceOf(Customizer::class, $theme->customizer);
        $this->assertInstanceOf(Menu::class, $theme->menu);
        $this->assertInstanceOf(Template::class, $theme->template);
        $this->assertInstanceOf(Widgets::class, $theme->widgets);
    }

    public function test_load_options() {
        /** @var Theme $theme */
        $theme = $this->new_instance_without_constructor(Theme::class, function (ReflectionClass $theme) {
            $prop = $theme->getProperty('cached');
            $prop->setAccessible(true);

            $info = array_merge($this->transient, [
                'parent_dir' => dirname(__DIR__).'/stubs',
            ]);

            $prop->setValue($theme, (object) ['info' => $info]);
        });

        $theme->load_options();

        $this->assertCount(4, $theme->options());
    }
}

class DummyFeature extends Feature {
    public function initialize() : void {
        // .
    }
}

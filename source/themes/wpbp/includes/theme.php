<?php
/**
 * WPBP Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

namespace WPBP;

/**
 * Theme Setup Class.
 *
 * @category Theme Setup
 * @property-read string $siteurl
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $child_slug
 * @property-read string $theme_uri
 * @property-read string $version
 * @property-read string $author
 * @property-read string $author_uri
 * @property-read string $parent_dir
 * @property-read string $child_dir
 * @property-read string $parent_uri
 * @property-read string $child_uri
 * @property-read Asset $asset
 * @property-read Comment $comment
 * @property-read Content $content
 * @property-read Customizer $customizer
 * @property-read Menu $menu
 * @property-read Option $option
 * @property-read Template $template
 * @property-read Widgets $widgets
 * @property-read Integrations\JetPack $jetpack
 */
final class Theme {
	/**
	 * Transient Names.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	const TRANSIENT_NAMES = [
		'theme_info',
		'icons_info',
		'styles_info',
	];

	/**
	 * Cache object.
	 *
	 * @since 0.1.0
	 * @var object
	 */
	private static $cached;

	/**
	 * Theme instances container.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $instances = [];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		self::$cached = (object) [];

		$transient_name           = $this->transient_name( 'theme_info' );
		self::$cached->theme_info = get_transient( $transient_name );

		if ( empty( self::$cached->theme_info ) ) {
			/** @var WP_Theme $theme */
			$theme      = wp_get_theme( get_template() );
			$theme_info = [
				'siteurl'    => get_option( 'siteurl' ),
				'name'       => $theme->name,
				'slug'       => $theme->template,
				'child_slug' => get_option( 'stylesheet' ),
				'theme_uri'  => $theme->get( 'ThemeURI' ),
				'version'    => $theme->version,
				'author'     => $theme->get( 'Author' ),
				'author_uri' => $theme->get( 'AuthorURI' ),
				'parent_dir' => trailingslashit( get_template_directory() ),
				'child_dir'  => trailingslashit( get_stylesheet_directory() ),
				'parent_uri' => trailingslashit( get_template_directory_uri() ),
				'child_uri'  => trailingslashit( get_stylesheet_directory_uri() ),
			];

			self::$cached->theme_info = $theme_info;
			set_transient( $transient_name, $theme_info );
		}

		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		if ( function_exists( 'tgmpa' ) ) {
			add_action( 'tgmpa_register', [ $this, 'tgmpa_register' ] );
		}

		$this->initialize( [
			Asset::class,
			Template::class,
			Customizer::class,
			Content::class,
			Comment::class,
			Menu::class,
			Option::class,
			Widgets::class,
		] );

		/**
		 * Load Jetpack compatibility file.
		 */
		if ( defined( 'JETPACK__VERSION' ) ) {
			$this->jetpack = Integrations\JetPack::class;
		}
	}

	/**
	 * Theme option panel.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_page/
	 * @since 0.1.0
	 * @return mixed
	 */
	public function admin_menu() {
		$page = add_theme_page(
			/* translators: %s: Theme name. */
			sprintf( __( '%s Option Panel', 'wpbp' ), $this->name ),
			__( 'Theme Option', 'wpbp' ),
			'edit_theme_options',
			$this->slug . '-options',
			function () {
				include_once $this->get_dir( 'templates/admin/options.php' );
			}
		);

		add_action( 'load-' . $page, [ $this, 'admin_help_tabs' ] );
	}

	/**
	 * Admin Help tab.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function admin_help_tabs() {
		$screen = get_current_screen();

		$screen->add_help_tab( [
			'id'       => $this->slug . '-option-help-tab',
			// translators: %s Theme name.
			'title'    => sprintf( __( '%s helps', 'wpbp' ), $this->name ),
			'callback' => function () {
				include_once $this->get_dir( 'templates/admin/help_tab.php' );
			},
		] );
	}

	/**
	 * Get specific transient name.
	 *
	 * @since 0.1.0
	 * @param  string $name
	 * @return mixed
	 */
	public function transient_name( string $name ) {
		if ( ! property_exists( self::$cached, 'transient_name' ) ) {
			self::$cached->transient_names = preg_filter( '/^/', 'wpbp_', array_combine(
				self::TRANSIENT_NAMES,
				self::TRANSIENT_NAMES
			) );
		}

		return self::$cached->transient_names[ $name ] ?? null;
	}

	/**
	 * Get theme info.
	 *
	 * @since 0.1.0
	 * @param  string $name
	 * @return string|null
	 */
	public function info( string $name ) {
		return self::$cached->theme_info[ $name ] ?? null;
	}

	/**
	 * Determine page template in use.
	 *
	 * @param  string $template_name
	 * @return bool
	 */
	public function is_template( $template_name ) : bool {
		return $this->get_template_basename() === $template_name;
	}

	/**
	 * Get main template.
	 *
	 * @see Template::$filename
	 *
	 * @return string|null
	 */
	public function get_template_filename() : ?string {
		return $this->template->filename;
	}

	/**
	 * Get base template.
	 *
	 * @see Template::$basename
	 *
	 * @return string|null
	 */
	public function get_template_basename() : ?string {
		return $this->template->basename;
	}

	/**
	 * Get parent template directory.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_dir( string $suffix ) : string {
		return $this->parent_dir . $suffix;
	}

	/**
	 * Get child stylesheet directory.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_child_dir( string $suffix ) : string {
		return $this->child_dir . $suffix;
	}

	/**
	 * Get parent template directory uri.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_uri( string $suffix ) : string {
		return $this->parent_uri . $suffix;
	}

	/**
	 * Get child stylesheet directory uri.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_child_uri( string $suffix ) : string {
		return $this->child_uri . $suffix;
	}

	/**
	 * Get asset file uri.
	 *
	 * @since 0.1.0
	 * @param  string $filename
	 * @return string
	 */
	public function get_assets_uri( string $filename ) : string {
		extract( pathinfo( $filename ) ); // phpcs:ignore WordPress.PHP.DontExtract
		$is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;

		if ( ! in_array( $extension, [ 'js', 'css' ], true ) ) {
			$extension = 'img';
		} else {
			$basename = $filename . ( $is_debug ? ".$extension" : ".min.$extension" );
		}

		return $this->get_uri( "assets/$extension/$basename" );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function setup() : void {
		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 */
		load_theme_textdomain( $this->slug, $this->get_child_dir( 'languages' ) );
		load_theme_textdomain( $this->slug, $this->get_dir( 'languages' ) );

		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://codex.wordpress.org/Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Declare support for title theme feature.
		 *
		 * @link https://codex.wordpress.org/Title_Tag
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Declare support for selective refreshing of widgets.
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for the Site Logo plugin and the site logo functionality in JetPack
		 *
		 * @link https://github.com/automattic/site-logo
		 * @link http://jetpack.me/
		 */
		add_theme_support(
			'site-logo',
			apply_filters( 'wpbp_support_site_logo_args', [
				'size' => 'full',
			] )
		);

		/**
		 * Enable support for site logo.
		 */
		add_theme_support(
			'custom-logo',
			apply_filters( 'wpbp_support_custom_logo_args', [
				'height'      => 110,
				'width'       => 470,
				'flex-width'  => true,
				'flex-height' => true,
			] )
		);

		/**
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			apply_filters( 'wpbp_support_html5_args', [
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			] )
		);

		/**
		 * Setup the WordPress core custom background feature.
		 *
		 * @see https://codex.wordpress.org/Custom_Backgrounds
		 */
		add_theme_support(
			'custom-background',
			apply_filters( 'wpbp_support_custom_background_args', [
				'default-color' => apply_filters( 'wpbp_support_default_background_color', 'ffffff' ),
				'default-image' => '',
			] )
		);

		/**
		 * Setup the WordPress core custom header feature.
		 *
		 * @link https://codex.wordpress.org/Custom_Headers
		 */
		add_theme_support(
			'custom-header',
			apply_filters( 'wpbp_support_custom_header_args', [
				'default-image'     => '',
				'header-text'       => false,
				'width'             => 1950,
				'height'            => 500,
				'flex-width'        => true,
				'flex-height'       => true,
				'wp-head-callback'  => [ $this->asset, 'custom_header' ],
			] )
		);
	}

	/**
	 * Register required plugins
	 *
	 * @return void
	 */
	public function tgmpa_register() {
		tgmpa( [
			[
				'name' => 'JetPack by Automatic',
				'slug' => 'jetpack',
			],
		] );
	}

	/**
	 * Register default theme options.
	 *
	 * @since 0.1.1
	 * @param  array $options
	 * @return void
	 */
	public function register_options( array $options = [] ) : void {
		foreach ( $options as $name => $attributes ) {
			$this->option->add( $name, $attributes );
		}
	}

	/**
	 * Get Theme Modification value.
	 *
	 * @since 0.1.1
	 * @param  string $name
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function get_mod( $name, $default = null ) {
		$theme_mods = get_theme_mods()[ $this->slug ] ?? [];
		$theme_mod  = apply_filters( 'wpbp_default_' . $name, ( $theme_mods[ $name ] ?? null ) );

		return $theme_mod ?: $default;
	}

	/**
	 * Class initializer.
	 *
	 * @since 0.1.1
	 * @param  array $classes
	 * @return void
	 */
	private function initialize( array $classes ) {
		foreach ( $classes as $name => $class ) {
			if ( is_numeric( $name ) ) {
				$name = strtolower(
					str_replace( [ '\\', __NAMESPACE__ . '.' ], [ '.', '' ], $class )
				);
			}

			$this->$name = $class;
		}
	}

	/**
	 * Setup class instances.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string          $name
	 * @param  string|\Closure $instance
	 * @throws \InvalidArgumentException If argument invalid.
	 */
	public function __set( string $name, $instance ) {
		if ( array_key_exists( $name, $this->instances ) ) {
			return;
		}

		if ( $instance instanceof \Closure ) {
			$instance = $instance( $this );
		}

		$error = 'Theme extension class should be Closure, string or instance of %1$s, %2$s given';

		if ( is_string( $instance ) && class_exists( $instance ) ) {
			$instance = new $instance( $this );
		} else {
			throw new \InvalidArgumentException(
				sprintf( $error, Feature::class, gettype( $instance ) )
			);
		}

		if ( ! ( $instance instanceof Feature ) ) {
			throw new \InvalidArgumentException(
				sprintf( $error, Feature::class, gettype( $instance ) )
			);
		}

		$this->instances[ $name ] = $instance;
	}

	/**
	 * Get class instance by $name.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string $name
	 * @return Feature|string|null
	 */
	public function __get( string $name ) {
		if ( array_key_exists( $name, self::$cached->theme_info ) ) {
			return self::$cached->theme_info[ $name ];
		}

		if ( array_key_exists( $name, $this->instances ) ) {
			return $this->instances[ $name ];
		}

		return null;
	}
}

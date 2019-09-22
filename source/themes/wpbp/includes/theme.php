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
 * @category    Theme Setup
 * @property-read Wrapper $wrapper
 * @property-read Integrations\Customizer $customizer
 * @property-read Integrations\JetPack $jetpack
 */
final class Theme {
	/**
	 * Transient Names.
	 *
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
	 * @var object
	 */
	private static $cached;

	/**
	 * Theme instances container.
	 *
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

		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_head', [ $this, 'head' ] );

		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_filter( 'set_url_scheme', [ $this, 'set_url_scheme' ], 10 );

		$this->initialize( [
			Wrapper::class,
			Customizer::class,
			Comment::class,
			Style::class,
			Script::class,
			Menu::class,
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
	 * @return mixed
	 */
	public function info( string $name ) {
		$transient_name = $this->transient_name( 'theme_info' );
		$theme_info     = self::$cached->theme_info ?? get_transient( $transient_name );

		if ( empty( $theme_info ) ) {
			$theme = wp_get_theme( get_template() );

			$theme_info = [
				'siteurl'              => get_option( 'siteurl' ),
				'child_slug'           => get_option( 'stylesheet' ),
				'version'              => $theme->get( 'Version' ),
				'parent_directory'     => trailingslashit( get_template_directory() ),
				'child_directory'      => trailingslashit( get_stylesheet_directory() ),
				'parent_directory_uri' => trailingslashit( get_template_directory_uri() ),
				'child_directory_uri'  => trailingslashit( get_stylesheet_directory_uri() ),
			];

			self::$cached->theme_info = $theme_info;
			set_transient( $transient_name, $theme_info );
		}

		return $theme_info[ $name ] ?? null;
	}

	/**
	 * Get main template.
	 *
	 * @see Wrapper::get_main()
	 *
	 * @return string
	 */
	public function get_main_template() {
		return $this->wrapper->get_main();
	}

	/**
	 * Get base template.
	 *
	 * @see Wrapper::get_base()
	 *
	 * @return string
	 */
	public function get_base_template() {
		return $this->wrapper->get_base();
	}

	/**
	 * Get parent template directory.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_parent_directory( string $suffix ) {
		$suffix = $this->info( 'parent_directory' ) . $suffix;

		return str_replace( '/', DIRECTORY_SEPARATOR, $suffix );
	}

	/**
	 * Get child stylesheet directory.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_child_directory( string $suffix ) {
		$suffix = $this->info( 'child_directory' ) . $suffix;

		return str_replace( '/', DIRECTORY_SEPARATOR, $suffix );
	}

	/**
	 * Get parent template directory uri.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_parent_directory_uri( string $suffix ) {
		return trailingslashit( get_template_directory_uri() ) . $suffix;
	}

	/**
	 * Get child stylesheet directory uri.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_child_directory_uri( string $suffix ) {
		return trailingslashit( get_stylesheet_directory_uri() ) . $suffix;
	}

	/**
	 * Get asset file uri.
	 *
	 * @since 0.1.0
	 * @param  string $filename
	 * @return string
	 */
	public function assets_url( string $filename ) {
		$extension = pathinfo( $filename, PATHINFO_EXTENSION );

		if ( ! in_array( $extension, [ 'js', 'css' ], true ) ) {
			$extension = 'img';
		}

		return $this->get_parent_directory_uri( 'assets/' . $extension . '/' . $filename );
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
	public function setup() {
		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 */
		load_theme_textdomain( 'wpbp', $this->get_child_directory( 'languages' ) );
		load_theme_textdomain( 'wpbp', $this->get_parent_directory( 'languages' ) );

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
		 *  Add support for the Site Logo plugin and the site logo functionality in JetPack
		 *
		 * @link https://github.com/automattic/site-logo
		 * @link http://jetpack.me/
		 */
		add_theme_support(
			'site-logo',
			apply_filters( 'wpbp_site_logo_args', [
				'size' => 'full',
			] )
		);

		/**
		 * Enable support for site logo.
		 */
		add_theme_support(
			'custom-logo',
			apply_filters( 'wpbp_custom_logo_args', [
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
			apply_filters( 'wpbp_html5_args', [
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
			apply_filters( 'wpbp_custom_background_args', [
				'default-color' => apply_filters( 'wpbp_default_background_color', 'ffffff' ),
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
			apply_filters( 'wpbp_custom_header_args', [
				'default-image'     => '',
				'header-text'       => false,
				'width'             => 1950,
				'height'            => 500,
				'flex-width'        => true,
				'flex-height'       => true,
				'wp-head-callback'  => [ $this->style, 'custom_header' ],
			] )
		);
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
		if ( ! isset( self::$cached->mods ) ) {
			self::$cached->mods = get_theme_mods()['wpbp'];
		}

		return self::$cached->mods[ $name ] ?? apply_filters( 'wpbp_default_' . $name, $default );
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function head() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @internal
	 * @since 0.1.0
	 * @param  array $classes
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'main-sidebar' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	/**
	 * Customize root url on development mode.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string $url
	 * @return string
	 */
	public function set_url_scheme( $url ) {
		$home = env( 'WP_HOME' );
		$url  = str_replace( [
			env( 'WP_SITEURL' ),
			env( 'WP_HOME' ),
		], [ $home, $home ], $url );

		if ( env( 'WP_ENV' ) === 'development' ) {
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput
			$url = str_replace( $home, 'http://' . $_SERVER['HTTP_HOST'], $url );
			// phpcs:enable
		}

		return $url;
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

		if ( is_string( $instance ) && class_exists( $instance ) ) {
			$instance = new $instance( $this );
		} else {
			throw new \InvalidArgumentException( 'Setup instance error.' );
		}

		$this->instances[ $name ] = $instance;
	}

	/**
	 * Get class instance by $name.
	 *
	 * @internal
	 * @since 0.1.1
	 * @param  string $name
	 * @return mixed
	 */
	public function __get( string $name ) {
		if ( ! array_key_exists( $name, $this->instances ) ) {
			return null;
		}

		return $this->instances[ $name ];
	}
}

<?php
/**
 * WPBP Theme.
 *
 * @package     WordPress_Boilerplate
 * @subpackage  WPBP_Theme
 * @since       0.1.0
 */

namespace WPBP;

/**
 * Theme Setup Class.
 *
 * @subpackage    Theme Setup
 * @property-read Wrapper $wrapper
 * @property-read Integrations\Customizer $customizer
 * @property-read Integrations\JetPack $jetpack
 */
class Theme {
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
	protected $instances = [];

	/**
	 * Initialize class.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		self::$cached = (object) [];

		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_head', [ $this, 'head' ] );
		add_action( 'login_head', [ $this, 'login_head' ] );

		add_filter( 'body_class', [ $this, 'body_classes' ] );

		$this->wrapper    = Wrapper::class;
		$this->customizer = Integrations\Customizer::class;

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
		return $this->info( 'parent_directory_uri' ) . $suffix;
	}

	/**
	 * Get child stylesheet directory uri.
	 *
	 * @since 0.1.0
	 * @param  string $suffix
	 * @return string
	 */
	public function get_child_directory_uri( string $suffix ) {
		return $this->info( 'child_directory_uri' ) . $suffix;
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
		 * Add support for Block Styles.
		 */
		add_theme_support( 'wp-block-styles' );

		/**
		 * Add support for full and wide align images.
		 */
		add_theme_support( 'align-wide' );

		/**
		 * Add support for editor styles.
		 */
		add_theme_support( 'editor-styles' );

		/**
		 * Enqueue editor styles.
		 */
		add_editor_style( [
			$this->assets_url( 'gutenberg-editor.css' ),
			$this->google_fonts_url(),
		] );

		/**
		 * Add support for editor font sizes.
		 */
		add_theme_support(
			'editor-font-sizes',
			apply_filters( 'wpbp_editor_font_sizes_args', [
				[
					'name' => __( 'Small', 'wpbp' ),
					'size' => 12,
					'slug' => 'small',
				],
				[
					'name' => __( 'Normal', 'wpbp' ),
					'size' => 14,
					'slug' => 'normal',
				],
				[
					'name' => __( 'Medium', 'wpbp' ),
					'size' => 20,
					'slug' => 'medium',
				],
				[
					'name' => __( 'Large', 'wpbp' ),
					'size' => 26,
					'slug' => 'large',
				],
				[
					'name' => __( 'Huge', 'wpbp' ),
					'size' => 32,
					'slug' => 'huge',
				],
			] )
		);

		/**
		 * Add support for responsive embedded content.
		 */
		add_theme_support( 'responsive-embeds' );

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
				'wp-head-callback'  => [ $this, 'custom_header_style' ],
			] )
		);

		/**
		 * Register menu locations.
		 */
		register_nav_menus(
			apply_filters( 'wpbp_register_nav_menus_args', [
				'primary' => __( 'Primary Menu', 'wpbp' ),
				'footer'  => __( 'Footer Menu', 'wpbp' ),
			] )
		);
	}

	/**
	 * Custom login hook.
	 *
	 * @return void
	 */
	public function login_head() {
		$theme_version = $this->info( 'version' );
		$login_style   = $this->css_login_style();

		if ( $login_style ) {
			wp_register_style( 'wpbp-custom-login', false, [], $theme_version );
			wp_add_inline_style( 'wpbp-custom-login', $this->css_login_style() );

			wp_enqueue_style( 'wpbp-custom-login' );
		}
	}

	/**
	 * Set up the WordPress core custom header feature.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function custom_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		$custom_header_style = [ '<style id="custom-header-style" type="text/css">' ];

		if ( ! display_header_text() ) {
			$custom_header_style[] = '.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
		} else {
			$custom_header_style[] = '.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }';
		}

		$custom_header_style[] = '</style>';

		echo implode( '', $custom_header_style ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function widgets_init() {
		register_sidebar( [
			'name'          => esc_html__( 'Main Sidebar', 'wpbp' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'Main sidebar that placed on the side of your page.', 'wpbp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section> <!-- #%1$s -->',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );

		register_sidebar( [
			'name'          => esc_html__( 'Footer Widgets', 'wpbp' ),
			'id'            => 'footer-widgets',
			'description'   => esc_html__( 'Footer widget that placed on the bottom of your page.', 'wpbp' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section> <!-- #%1$s -->',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );
	}

	/**
	 * Get dynamyc sidebar.
	 *
	 * @param  int|string $index
	 * @return void
	 */
	public function get_active_widgets( $index ) {
		if ( ! is_active_sidebar( $index ) ) {
			return;
		}

		dynamic_sidebar( $index );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue_scripts() {
		$theme_version = $this->info( 'version' );

		wp_register_style( 'wpbp-google-fonts', $this->google_fonts_url(), [], $theme_version );

		wp_register_style( 'wpbp-css-variables', false, [], $theme_version );
		wp_add_inline_style( 'wpbp-css-variables', $this->css_variables() );

		wp_enqueue_style( 'wpbp-style', $this->assets_url( 'main.css' ), [ 'wpbp-google-fonts', 'wpbp-css-variables' ], $theme_version );
		wp_style_add_data( 'wpbp-style', 'rtl', 'replace' );

		wp_enqueue_script( 'wpbp-script', $this->assets_url( 'navigation.js' ), [], $theme_version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Theme CSS Variables
	 *
	 * @internal
	 * @since 0.1.0
	 * @return string
	 */
	protected function css_variables() {
		return Style::make( [
			':root' => [
				'--white-color'            => $this->get_mod( 'custom_white_color', '#fff' ),
				'--gray-color'             => $this->get_mod( 'custom_gray_color', '#6c757d' ),
				'--gray-dark-color'        => $this->get_mod( 'custom_gray_dark_color', '#343a40' ),
				'--primary-color'          => $this->get_mod( 'custom_primary_color', '#007bff' ),
				'--secondary-color'        => $this->get_mod( 'custom_secondary_color', '#6c757d' ),
				'--success-color'          => $this->get_mod( 'custom_success_color', '#28a745' ),
				'--info-color'             => $this->get_mod( 'custom_info_color', '#17a2b8' ),
				'--warning-color'          => $this->get_mod( 'custom_warning_color', '#ffc107' ),
				'--danger-color'           => $this->get_mod( 'custom_danger_color', '#dc3545' ),
				'--light-color'            => $this->get_mod( 'custom_light_color', '#f8f9fa' ),
				'--dark-color'             => $this->get_mod( 'custom_dark_color', '#343a40' ),

				'--breakpoint-xs'          => '0',
				'--breakpoint-sm'          => $this->get_mod( 'custom_breakpoint_sm', '576px' ),
				'--breakpoint-md'          => $this->get_mod( 'custom_breakpoint_md', '768px' ),
				'--breakpoint-lg'          => $this->get_mod( 'custom_breakpoint_lg', '992px' ),
				'--breakpoint-xl'          => $this->get_mod( 'custom_breakpoint_xl', '1200px' ),

				'--font-family-sans-serif' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"',
				'--font-family-monospace'  => 'SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace',

				'--link-color'             => $this->get_mod( 'custom_link_color', '#007bff' ),
				'--link-hover-color'       => $this->get_mod( 'custom_link_hover_color', '#007bff' ),
				'--link-active-color'      => $this->get_mod( 'custom_link_active_color', '#007bff' ),

				'--background-color'       => get_theme_mod( 'background_color', '#fff' ),
				'--border-color'           => $this->get_mod( 'custom_border_color', '#007bff' ),
				'--text-color'             => $this->get_mod( 'custom_text_color', '#343a40' ),
				'--paragraph-color'        => $this->get_mod( 'custom_paragraph_color', '#343a40' ),
				'--heading-color'          => $this->get_mod( 'custom_heading_color', '#343a40' ),
			],
		] );
	}

	/**
	 * Custom login style.
	 *
	 * @return string
	 */
	public function css_login_style() {
		$custom_logo_id  = get_theme_mod( 'custom_logo' );
		$custom_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );

		if ( ! $custom_logo_url ) {
			return null;
		}

		return Style::make( function ( Style $style ) use ( $custom_logo_url ) {
			return [
				'.login h1 a' => [
					'background-image' => $style->url( $custom_logo_url ) . ' !important',
				],
			];
		} );
	}

	/**
	 * Get Theme Modification value.
	 *
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
	 * Register Google fonts.
	 *
	 * @since 0.1.0
	 * @return string Google fonts URL for the theme.
	 */
	public function google_fonts_url() {
		$google_fonts = apply_filters( 'wpbp_google_font_families', [
			'source-sans-pro' => 'Source+Sans+Pro:400,300,300italic,400italic,600,700,900',
		] );

		$query_args = [
			'family' => implode( '|', $google_fonts ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
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
	 * Setup class instances.
	 *
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

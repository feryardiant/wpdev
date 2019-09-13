<?php
/**
 * Starter Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Starter_Theme
 */

if ( ! function_exists( 'starter' ) ) {
	/**
	 * Main theme object.
	 *
	 * @param string|null $key Theme object key.
	 */
	function starter( $key = null ) {
		$theme         = wp_get_theme( 'starter' );
		$starter_theme = [
			'version' => $theme['Version'],
		];

		if ( $key && array_key_exists( $key, $starter_theme ) ) {
			return $starter_theme[ $key ];
		}

		return (object) $starter_theme;
	}
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
}

if ( ! function_exists( 'starter_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function starter_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Starter Theme, use a find and replace
		 * to change 'starter' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'starter', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( [
			'menu-1' => esc_html__( 'Primary', 'starter' ),
		] );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		] );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'starter_custom_background_args', [
			'default-color' => 'ffffff',
			'default-image' => '',
		] ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', [
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		] );
	}
endif;
add_action( 'after_setup_theme', 'starter_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function starter_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'starter_content_width', 640 );
}
add_action( 'after_setup_theme', 'starter_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function starter_widgets_init() {
	register_sidebar( [
		'name'          => esc_html__( 'Sidebar', 'starter' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'starter' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	] );
}
add_action( 'widgets_init', 'starter_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function starter_scripts() {
	global $starter_theme;

	wp_enqueue_style( 'starter-style', get_stylesheet_uri(), [], starter( 'version' ) );

	wp_enqueue_script( 'starter-navigation', get_template_directory_uri() . '/js/navigation.js', [], starter( 'version' ), true );

	wp_enqueue_script( 'starter-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', [], starter( 'version' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'starter_scripts' );

$starter_inc_directory = get_template_directory() . '/inc';

/**
 * Implement the Custom Header feature.
 */
require $starter_inc_directory . '/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require $starter_inc_directory . '/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require $starter_inc_directory . '/template-functions.php';

/**
 * Customizer additions.
 */
require $starter_inc_directory . '/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require $starter_inc_directory . '/jetpack.php';
}


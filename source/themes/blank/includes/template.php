<?php
/**
 * Blank Theme.
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.1
 */

namespace Blank;

/**
 * Theme Template Features.
 *
 * @category  Theme Template
 */
class Template extends Feature {
	/**
	 * Template file name.
	 *
	 * @internal
	 * @since 0.1.1
	 * @var string
	 */
	public $filename;

	/**
	 * Template base name.
	 *
	 * @internal
	 * @since 0.1.1
	 * @var string
	 */
	public $basename;

	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_head', [ $this, 'head' ] );
		add_action( 'blank_skip_link', [ $this, 'skip_link' ], 10, 1 );
		add_action( 'blank_hero_body', [ $this, 'hero_body' ], 10 );
		add_action( 'blank_before_main', [ $this, 'before_main' ], 10 );
		add_action( 'blank_before_content', [ $this, 'before_content' ], 10 );
		add_action( 'blank_after_content', [ $this, 'after_content' ], 10, 0 );
		add_action( 'blank_after_main', [ $this, 'after_main' ], 10, 0 );
		add_action( 'blank_footer', [ $this, 'footer_widgets' ], 10, 0 );
		add_action( 'blank_footer', [ $this, 'footer_credit' ], 20, 0 );

		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_filter( 'get_custom_logo', [ $this, 'site_branding' ] );
		add_filter( 'template_include', [ $this, 'wrapper' ] );
	}

	/**
	 * Template Setup
	 *
	 * @return void
	 */
	public function setup() : void {
		// .
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @internal
	 * @since 0.1.0
	 * @return void
	 */
	public function head() : void {
		$viewport = [
			'width'         => 1024,
			'initial-scale' => '1',
		];

		if ( $this->theme->get_option( 'general_layout_enable_responsive' ) ) {
			$viewport['width']         = 'device-width';
			$viewport['maximum-scale'] = '2.0';
		}

		printf(
			'<meta name="viewport" content="%s">',
			esc_attr( $this->html_attributes_from_array( $viewport, ', ', false ) )
		);

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
	public function body_classes( $classes ) : array {
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
	 * Site Branding.
	 *
	 * @since 0.1.1
	 * @return string
	 */
	public function site_branding() {
		$site_name = $this->site_name();
		$site_desc = $this->site_slogan();
		$site_logo = $this->site_logo();
		$output    = [];

		if ( $site_logo ) {
			$logo_attr = [
				'class' => 'custom-logo',
				'alt'   => get_post_meta( $site_logo->ID, '_wp_attachment_image_alt', true ),
			];

			if ( ! $logo_attr['alt'] ) {
				$logo_attr['alt'] = $site_name;
			}

			$output[] = sprintf(
				'<img %1$s src="%2$s"/>',
				$this->html_attributes_from_array( $logo_attr ),
				esc_url( $site_logo->src )
			);
		}

		$output[] = '<h1 class="site-title">' . $site_name . '</h1>';

		if ( $this->theme->get_option( 'show_tagline' ) ) {
			$output[] = '<p class="site-description">' . $site_desc . '</p>';
		}

		$attr = [
			'id'    => 'branding',
			'href'  => esc_url( home_url( '/' ) ),
			'class' => 'navbar-item',
		];

		$kses = [
			'a' => [
				'rel'   => [],
				'id'    => [],
				'href'  => [],
				'class' => [],
			],
			'h1' => [
				'class' => [],
			],
			'p' => [
				'class' => [],
			],
			'img' => [
				'src'   => [],
				'alt'   => [],
				'class' => [],
			],
		];

		return wp_kses( sprintf(
			'<a %1$s rel="home">%2$s</a> <!-- %3$s -->',
			$this->html_attributes_from_array( $attr ),
			join( '', $output ),
			'#' . esc_attr( $attr['id'] )
		), $kses );
	}

	/**
	 * Render Site Name.
	 *
	 * @return string
	 */
	public function site_name() {
		return get_bloginfo( 'name', 'display' );
	}

	/**
	 * Render Site Description.
	 *
	 * @return string
	 */
	public function site_slogan() {
		return get_bloginfo( 'description', 'display' );
	}

	/**
	 * Get the custom site logo.
	 *
	 * @param  string $size
	 * @return object|null
	 */
	public function site_logo( string $size = 'full' ) {
		$id = get_theme_mod( 'custom_logo' );

		if ( ! $id ) {
			return null;
		}

		return (object) [
			'ID'  => $id,
			'src' => wp_get_attachment_image_url( $id, $size ),
		];
	}

	/**
	 * Template Wrapper
	 *
	 * @param  string $template
	 * @return string
	 */
	public function wrapper( $template ) {
		$this->filename = $template;
		$this->basename = substr( wp_basename( $this->filename ), 0, -4 );

		return $template;
	}

	/**
	 * Print the skip-link.
	 *
	 * @since 0.1.1
	 * @param  string $target_id
	 * @return void
	 */
	public function skip_link( string $target_id = 'site-content' ) {
		$text = apply_filters( 'blank_skip_link_text', __( 'Skip to content', 'blank' ) );

		echo '<a class="skip-link screen-reader-text" href="#' . esc_attr( $target_id ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Print element before main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function hero_body() {
		$query    = $this->theme->content->page_query();
		$title    = get_the_title( $query );
		$subtitle = $query ? $query->post_excerpt : '';

		$not_found_subtitle = __( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'blank' );

		if ( is_search() ) {
			$title = sprintf(
				/* translators: %s: search query. */
				esc_html__( 'Search Results for: %s', 'blank' ),
				'<span>' . get_search_query() . '</span>'
			);
		} elseif ( is_404() ) {
			$title    = esc_html__( 'Oops! That page can&rsquo;t be found.', 'blank' );
			$subtitle = $not_found_subtitle;
		} elseif ( is_archive() && ! is_post_type_archive() ) {
			$title    = get_the_archive_title();
			$subtitle = get_the_archive_description();
		}

		$kses = [
			'h1' => [
				'class' => [],
			],
			'span' => [
				'class' => [],
			],
		];

		echo wp_kses( sprintf(
			'<h1 class="title">%1$s</h1><span class="subtitle">%2$s</span>',
			$title,
			$subtitle
		), $kses );
	}

	/**
	 * Before main section.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function before_main() {
		echo '<div class="container">';
		echo '<div class="columns">';
	}

	/**
	 * Before main section.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function after_main() {
		echo '</div> <!-- .columns -->';
		echo '</div> <!-- .container --!>';
	}

	/**
	 * Print element before main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function before_content() {
		$main_classes = [ 'column' ];

		if ( 'full-width' !== $this->basename ) {
			$main_classes[] = 'is-two-thirds';
		}

		$section_classes = [];

		$main_attr = [
			'id'    => 'primary',
			'role'  => 'main',
			'class' => (array) apply_filters( 'blank_main_class', $main_classes ),
		];

		$section_attr = [
			'id'    => 'main',
			'class' => (array) apply_filters( 'blank_section_class', $section_classes ),
		];

		$kses = [
			'main' => [
				'id'    => [],
				'class' => [],
				'role'  => [],
			],
			'section' => [
				'id'    => [],
				'class' => [],
			],
		];

		echo wp_kses( sprintf(
			'<main %1$s><section %2$s>',
			$this->html_attributes_from_array( $main_attr ),
			$this->html_attributes_from_array( $section_attr )
		), $kses );
	}

	/**
	 * Print element after main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function after_content() {
		echo '</section> <!-- #main.site-main -->';
		echo '</main> <!-- #primary -->';
	}

	/**
	 * Convert array to HTML attributes.
	 *
	 * @since 0.1.1
	 * @param array  $attr
	 * @param string $attr_sep
	 * @param bool   $quoted
	 * @return string
	 */
	public function html_attributes_from_array( array $attr, string $attr_sep = ' ', bool $quoted = true ) : string {
		$output = [];

		foreach ( array_filter( $attr ) as $name => $value ) {
			if ( is_array( $value ) ) {
				$value = join( ' ', $value );
			}

			$output[] = $name . '=' . ( $quoted ? '"' . $value . '"' : $value );
		}

		return join( $attr_sep, $output );
	}

	/**
	 * Print footer credit.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function footer_widgets() {
		$wrapper = apply_filters( 'blank_footer_widgets_wrapper', [
			'before' => '<div class="footer-widgets">',
			'after'  => '</div> <!-- .site-info --!>',
		] );

		echo wp_kses( $wrapper['before'], $this->common_kses() );

		Widgets::get_active( 'footer-widgets' );

		echo wp_kses( $wrapper['after'], $this->common_kses() );
	}

	/**
	 * Print footer credit.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function footer_credit() {
		$wrapper = apply_filters( 'blank_footer_credits_wrapper', [
			'before' => '<div class="site-info">',
			'after'  => '</div> <!-- .site-info --!>',
		] );

		$output = [
			$wrapper['before'],
		];

		$output[] = '<a target="__blank" href="' . esc_url( __( 'https://wordpress.org/', 'blank' ) ) . '">';
		/* translators: %s: Credit to WordPress. */
		$output[] = sprintf( esc_html__( 'Proudly powered by %s', 'blank' ), 'WordPress' );
		$output[] = '</a>';
		$output[] = '<span class="sep"> | </span>';

		$output[] = sprintf(
			/* translators: %1$s: Theme Name, %2$s: Theme Author. */
			esc_html__( '%1$s by %2$s.', 'blank' ),
			$this->theme->name,
			$this->theme->author
		);

		$output[] = $wrapper['before'];

		echo wp_kses( join( '', $output ), $this->common_kses() );
	}

	/**
	 * Common KSES.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_kses
	 * @return string
	 */
	public function common_kses() {
		return [
			'div' => [
				'class' => [],
			],
			'a' => [
				'target' => [],
				'href'   => [],
				'class'  => [],
			],
			'span' => [
				'class' => [],
			],
		];
	}
}

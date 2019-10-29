<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

use function Blank\Helpers\get_allowed_attr;
use function Blank\Helpers\get_schema_org_attr;
use function Blank\Helpers\make_attr_from_array;
use function Blank\Helpers\make_html_tag;

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
		add_action( 'wp_head', [ $this, 'head' ] );
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

		if ( $this->theme->get_option( 'enable_responsive' ) ) {
			$viewport['width']         = 'device-width';
			$viewport['maximum-scale'] = '2.0';
		}

		printf(
			'<meta name="viewport" content="%s">',
			esc_attr( make_attr_from_array( $viewport, ', ', false ) )
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
		$output    = [];
		$site_logo = $this->site_logo_image( [
			'alt' => $site_name,
		] );

		if ( $site_logo ) {
			$output[] = $site_logo;
		}

		if ( $this->theme->get_option( 'show_site_title' ) ) {
			$output[] = '<h1 class="site-title" itemprop="headline">' . $site_name . '</h1>';
		}

		if ( $this->theme->get_option( 'show_tagline' ) ) {
			$output[] = '<p class="site-description" itemprop="description">' . $site_desc . '</p>';
		}

		$attr = [
			'id'       => 'branding',
			'href'     => esc_url( home_url( '/' ) ),
			'class'    => 'site-identity',
			'itemtype' => 'http://schema.org/Organization',
		];

		if ( $site_logo && $this->theme->get_option( 'inline_site_title' ) ) {
			$attr['class'] = 'is-inline-title';
		}

		return sprintf(
			'<a %1$s rel="home">%2$s</a> <!-- %3$s -->',
			make_attr_from_array( $attr ),
			join( '', $output ),
			'#' . esc_attr( $attr['id'] )
		);
	}

	/**
	 * Render Site Name.
	 *
	 * @since 0.1.1
	 * @return string
	 */
	public function site_name() {
		return get_bloginfo( 'name', 'display' );
	}

	/**
	 * Render Site Description.
	 *
	 * @since 0.1.1
	 * @return string
	 */
	public function site_slogan() {
		return get_bloginfo( 'description', 'display' );
	}

	/**
	 * Get the custom site logo.
	 *
	 * @since 0.1.1
	 * @param  string $size
	 * @return object|null
	 */
	public function site_logo( string $size = 'full' ) {
		$logo = [
			'ID' => get_theme_mod( 'custom_logo' ) ?: null,
		];

		$logo['src'] = $logo['ID'] ? wp_get_attachment_image_url( $logo['ID'], $size ) : null;
		$logo        = apply_filters( 'blank_site_logo', $logo );

		if ( ! $logo || ! $logo['src'] ) {
			return null;
		}

		return (object) $logo;
	}

	/**
	 * Get the custom site logo image.
	 *
	 * @since 0.1.1
	 * @param  array $attr
	 * @param  bool  $returns
	 * @return string
	 */
	public function site_logo_image( array $attr = [], bool $returns = true ) : string {
		$site_logo = $this->site_logo();

		if ( ! $site_logo ) {
			return '';
		}

		$attr = wp_parse_args( $attr, [
			'class' => 'custom-logo',
			'src'   => esc_url( $site_logo->src ),
			'alt'   => get_post_meta( $site_logo->ID, '_wp_attachment_image_alt', true ),
		] );

		return make_html_tag( 'img', $attr, true, $returns );
	}

	/**
	 * Template Wrapper
	 *
	 * @since 0.1.1
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
	public function skip_link( string $target_id = 'content' ) {
		$text      = apply_filters( 'blank_skip_link_text', __( 'Skip to content', 'blank' ) );
		$target_id = apply_filters( 'blank_skip_link_target', $target_id );

		make_html_tag( 'a', [
			'class' => [ 'skip-link', 'screen-reader-text' ],
			'href'  => '#' . esc_attr( $target_id ),
		], $text, false );
	}

	/**
	 * Print template header.
	 *
	 * @since 0.2.2
	 * @return void
	 */
	public function header() {
		$this->skip_link();

		echo '<div class="container">';

		the_custom_logo();

		$this->primary_navigation();

		echo '</div>';
	}

	/**
	 * Print primary navitaion.
	 *
	 * @since 0.2.2
	 * @return void
	 */
	public function primary_navigation() {
		$attr = array_merge( [
			'class'      => 'site-navigation',
			'role'       => 'navigation',
			'aria-label' => __( 'Site Navigation', 'blank' ),
		], get_schema_org_attr( 'navigation' ) );

		make_html_tag( 'nav', $attr, [
			'button' => [
				'attr' => [
					'role'          => 'button',
					'class'         => 'menu-toggle',
					'aria-controls' => 'menu-primary',
					'aria-expanded' => 'false',
				],
				'ends' => [
					'span' => [
						'attr' => [ 'class' => 'mobile-menu' ],
						'ends' => false,
					],
				],
			],
			wp_nav_menu( [ 'theme_location' => 'primary', 'echo' => false ] ), // phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
		], false );
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

		make_html_tag( 'h1', [ 'class' => 'title' ], $title );
		make_html_tag( 'span', [ 'class' => 'subtitle' ], $subtitle );
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

		$main_attr = [
			'id'    => 'primary',
			'role'  => 'main',
			'class' => (array) apply_filters( 'blank_main_class', $main_classes ),
		];

		$kses = [
			'main' => [
				'id'    => [],
				'class' => [],
				'role'  => [],
			],
		];

		echo wp_kses( sprintf(
			'<main %1$s>',
			make_attr_from_array( $main_attr )
		), $kses );
	}

	/**
	 * Print element after main-content area.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function after_content() {
		echo '</main> <!-- #primary -->';
	}

	/**
	 * Print footer.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function footer() {
		$this->footer_widgets();

		$this->footer_info();
	}

	/**
	 * Print footer credit.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function footer_widgets() {
		if ( ! is_active_sidebar( 'footer-widgets' ) ) {
			return;
		}

		$wrapper = apply_filters( 'blank_footer_widgets_wrapper', [
			'before' => '<div class="footer-widgets">',
			'after'  => '</div> <!-- .site-info --!>',
		] );

		echo wp_kses( $wrapper['before'], $this->common_kses() );

		dynamic_sidebar( 'footer-widgets' );

		echo wp_kses( $wrapper['after'], $this->common_kses() );
	}

	/**
	 * Print footer credit.
	 *
	 * @since 0.1.1
	 * @return void
	 */
	public function footer_info() {
		$wrapper = apply_filters( 'blank_footer_info_wrapper', [
			'before' => '<section class="site-footer__info"> <div class="container"> <div class="wrapper">',
			'after'  => '</div> <!-- .wrapper --> </div> <!-- .container --> </section> <!-- .site-info --!>',
		] );

		echo wp_kses( $wrapper['before'], $this->common_kses() );

		wp_nav_menu( [
			'theme_location' => 'footer',
			'depth'          => 1,
		] );

		$logo_image = $this->site_logo_image( [ 'alt' => 'Footer Logo' ] );
		if ( $logo_image ) {
			make_html_tag( 'div', [ 'class' => 'branding' ], $logo_image, false );
		}

		make_html_tag( 'p', [ 'class' => 'credits' ], sprintf(
			/* translators: %s: Current Year and Site Title. */
			esc_html__( 'Copyright &copy; %s.', 'blank' ),
			date( 'Y' ) . ' ' . $this->site_name()
		), false );

		echo wp_kses( $wrapper['after'], $this->common_kses() );
	}

	/**
	 * Common KSES.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_kses
	 * @param string ...$attrs
	 * @return array
	 */
	public function common_kses( string ...$attrs ) : array {
		return get_allowed_attr(
			array_merge( [ 'div', 'a', 'span' ], $attrs )
		);
	}
}

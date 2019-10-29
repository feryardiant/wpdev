<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

namespace Blank;

use function Blank\Helpers\make_html_tag;

/**
 * Theme Content Class.
 *
 * @category  Theme Content
 */
class Content extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_filter( 'content_width', [ $this, 'content_width' ] );
		add_filter( 'post_class', [ $this, 'post_class' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names_choose' ] );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 *
	 * @internal
	 * @since 0.2.0
	 * @return void
	 */
	public function setup() : void {
		add_image_size( 'post_size', 600, null );
		add_image_size( 'max_size', 1600, 900, true );
	}

	/**
	 * Custom content-width.
	 *
	 * @link https://codex.wordpress.org/Content_Width
	 * @since 0.1.1
	 * @return int
	 */
	public function content_width() {
		return 700;
	}

	/**
	 * Additional post classes.
	 *
	 * @since 0.2.1
	 * @param array $classes
	 * @return int
	 */
	public function post_class( $classes ) {
		$classes[] = 'content';

		return $classes;
	}

	/**
	 * Customize image sizes name.
	 *
	 * @link https://gist.github.com/wycks/4949242
	 * @since 0.1.1
	 * @param  array $image_sizes
	 * @return array
	 */
	public function image_size_names_choose( array $image_sizes ) {
		$image_sizes['post_size'] = __( 'Post', 'blank' );

		return $image_sizes;
	}

	/**
	 * Custom content-width.
	 *
	 * @link https://codex.wordpress.org/Content_Width
	 * @since 0.1.1
	 * @return \WP_Post|null
	 */
	public function page_query() : ?\WP_Post {
		global $wp_query;

		$query = $wp_query->get_queried_object();

		if ( $query instanceof \WP_Post_Type ) {
			$query = get_page_by_path( $query->rewrite['slug'] );
		}

		return $query;
	}

	/**
	 * Print post date.
	 *
	 * @since 0.2.1
	 */
	public function header() {
		$this->title();

		if ( 'post' === get_post_type() ) {
			echo '<div class="entry-meta">';

			$this->posted_on();

			$this->posted_by();

			echo '</div> <!-- .entry-meta -->';
		}
	}

	/**
	 * Print post date.
	 *
	 * @since 0.2.1
	 */
	public function title() {
		if ( is_singular() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
	}

	/**
	 * Print post date.
	 *
	 * @since 0.2.1
	 */
	public function posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'blank' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Prints HTML with meta information for the current author.
	 *
	 * @since 0.2.1
	 */
	public function posted_by() {
		$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'blank' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( $author_url ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @since 0.2.1
	 */
	public function thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) {
			echo '<div class="post-thumbnail">' . get_the_post_thumbnail() . '</div> <!-- .post-thumbnail -->';
		} else {
			echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">';

			the_post_thumbnail( 'post-thumbnail', [
				'alt' => the_title_attribute( [
					'echo' => false,
				] ),
			] );

			echo '</a>  <!-- .post-thumbnail -->';
		}
	}

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 *
	 * @since 0.2.1
	 */
	public function link_pages() {
		wp_link_pages( [
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blank' ),
			'after'  => '</div>',
		] );
	}

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 *
	 * @since 0.2.1
	 */
	public function footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$this->categories();

			$this->tags();
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';

			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'blank' ),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					get_the_title()
				)
			);

			echo '</span>';
		}

		$this->edit_link();

		if ( is_singular() ) {
			the_posts_navigation();
		}
	}

	/**
	 * Prints HTML categories.
	 *
	 * @since 0.2.1
	 */
	public function categories() {
		$categories = get_the_category_list( ', ' );

		if ( $categories ) {
			printf(
				/* translators: 1: list of categories. */
				'<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'blank' ) . '</span>',
				$categories // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
	}

	/**
	 * Prints HTML tags.
	 *
	 * @since 0.2.1
	 */
	public function tags() {
		$tags = get_the_tag_list( '', ', ' );

		if ( $tags ) {
			printf(
				/* translators: 1: list of tags. */
				'<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'blank' ) . '</span>',
				$tags // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
	}

	/**
	 * Prints HTML edit link.
	 *
	 * @since 0.2.1
	 */
	public function edit_link() {
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'blank' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

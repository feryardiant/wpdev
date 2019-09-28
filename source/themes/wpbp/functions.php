<?php
/**
 * WPBP Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package    WordPress_Boilerplate
 * @subpackage WPBP_Theme
 * @since      0.1.0
 */

/**
 * Autoloader
 */
require_once __DIR__ . '/includes/autoload.php';

$wpbp_theme = new WPBP\Theme();

if ( ! function_exists( 'wpbp' ) ) {
	/**
	 * Main theme object.
	 *
	 * @param string|null $key Theme object key.
	 * @return WPBP\Theme
	 */
	function wpbp( $key = null ) {
		global $wpbp_theme;

		return $key ? $wpbp_theme->info( $key ) : $wpbp_theme;
	}
}

$dummy_section = [
	'dummy' => [
		'title'       => __( 'Dummy', 'wpbp' ),
		'description' => __( 'There\'s nothing to find here', 'wpbp' ),
		'settings'    => [
			'foo' => [
				'label'   => __( 'Lorem Ipsum', 'wpbp' ),
				'default' => '',
				'type'    => 'text',
			],
		],
	],
];

$wpbp_theme->register_options( [
	'show_tagline' => [
		'label'   => __( 'Show Tagline.', 'wpbp' ),
		'type'    => 'checkbox',
		'default' => true,
		'section' => 'title_tagline',
	],
	'site_logo_display' => [
		'label'   => __( 'Choose the option that you want.', 'wpbp' ),
		'type'    => 'radio',
		'default' => 'text_only',
		'section' => 'title_tagline',
		'choices' => [
			'logo_only' => __( 'Logo Image Only', 'wpbp' ),
			'text_only' => __( 'Logo Text Only', 'wpbp' ),
			'both'      => __( 'Show Both', 'wpbp' ),
			'none'      => __( 'Disable', 'wpbp' ),
		],
	],
	'link_color' => [
		'label'    => __( 'Link Color', 'wpbp' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'link_active_color' => [
		'label'    => __( 'Link Active Color', 'wpbp' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'link_hover_color' => [
		'label'    => __( 'Link Hover Color', 'wpbp' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'text_color' => [
		'label'    => __( 'Text Color', 'wpbp' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'paragraph_color' => [
		'label'    => __( 'Paragraph Color', 'wpbp' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'heading_color' => [
		'label'    => __( 'Heading Color', 'wpbp' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'general' => [
		'title'       => __( 'General Settings', 'wpbp' ),
		'description' => __( 'Global theme customization value', 'wpbp' ),
		'priority'    => 25,
		'sections'    => [
			'layout' => [
				'title'       => __( 'Layout', 'wpbp' ),
				'description' => __( 'Global layout for all pages', 'wpbp' ),
				'settings'    => [
					'container' => [
						'label'   => __( 'Global Container', 'wpbp' ),
						'type'    => 'radio',
						'default' => '',
						'choices' => [
							'wide'     => __( 'Wide', 'wpbp' ),
							'boxed'    => __( 'Boxed', 'wpbp' ),
							'fluid'    => __( 'Fluid', 'wpbp' ),
							'narrowed' => __( 'Narrowed', 'wpbp' ),
						],
					],
					'header' => [
						'label'   => __( 'Header Container', 'wpbp' ),
						'default' => '',
						'type'    => 'text',
					],
					'footer' => [
						'label'   => __( 'Footer Container', 'wpbp' ),
						'default' => '',
						'type'    => 'text',
					],
				],
			],
		],
	],
	'foo_1' => [
		'title'    => __( 'Header Setting', 'wpbp' ),
		'priority' => 25,
		'sections' => $dummy_section,
	],
	'foo_2' => [
		'title'    => __( 'Content Setting', 'wpbp' ),
		'priority' => 25,
		'sections' => $dummy_section,
	],
	'foo_3' => [
		'title'    => __( 'Footer Setting', 'wpbp' ),
		'priority' => 25,
		'sections' => $dummy_section,
	],
] );

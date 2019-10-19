<?php
/**
 * Blank Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package  Blank
 * @since    0.1.0
 */

/**
 * Autoloader
 */
require_once __DIR__ . '/includes/autoload.php';

$blank_theme = new Blank\Theme();

if ( ! function_exists( 'blank' ) ) {
	/**
	 * Main theme object.
	 *
	 * @param string|null $key Theme object key.
	 * @return Blank\Theme
	 */
	function blank( $key = null ) {
		global $blank_theme;

		return $key ? $blank_theme->info( $key ) : $blank_theme;
	}
}

$blank_dummy_section = [
	'dummy' => [
		'title'       => __( 'Dummy', 'blank' ),
		'description' => __( 'There\'s nothing to find here', 'blank' ),
		'settings'    => [
			'foo' => [
				'label'   => __( 'Lorem Ipsum', 'blank' ),
				'default' => '',
				'type'    => 'text',
			],
		],
	],
];

$blank_theme->register_options( [
	'show_tagline' => [
		'label'   => __( 'Show Tagline.', 'blank' ),
		'type'    => 'checkbox',
		'default' => true,
		'section' => 'title_tagline',
	],
	'site_logo_display' => [
		'label'   => __( 'Choose the option that you want.', 'blank' ),
		'type'    => 'radio',
		'default' => 'text_only',
		'section' => 'title_tagline',
		'choices' => [
			'logo_only' => __( 'Logo Image Only', 'blank' ),
			'text_only' => __( 'Logo Text Only', 'blank' ),
			'both'      => __( 'Show Both', 'blank' ),
			'none'      => __( 'Disable', 'blank' ),
		],
	],
	'link_color' => [
		'label'    => __( 'Link Color', 'blank' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'link_active_color' => [
		'label'    => __( 'Link Active Color', 'blank' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'link_hover_color' => [
		'label'    => __( 'Link Hover Color', 'blank' ),
		'default'  => '#007bff',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'text_color' => [
		'label'    => __( 'Text Color', 'blank' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'paragraph_color' => [
		'label'    => __( 'Paragraph Color', 'blank' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'heading_color' => [
		'label'    => __( 'Heading Color', 'blank' ),
		'default'  => '#000',
		'type'     => 'color',
		'section'  => 'colors',
	],
	'general' => [
		'title'       => __( 'General Settings', 'blank' ),
		'description' => __( 'Global theme customization value', 'blank' ),
		'priority'    => 25,
		'sections'    => [
			'layout' => [
				'title'       => __( 'Layout', 'blank' ),
				'description' => __( 'Global layout for all pages', 'blank' ),
				'settings'    => [
					'container' => [
						'label'   => __( 'Global Container', 'blank' ),
						'type'    => 'radio',
						'default' => '',
						'choices' => [
							'wide'     => __( 'Wide', 'blank' ),
							'boxed'    => __( 'Boxed', 'blank' ),
							'fluid'    => __( 'Fluid', 'blank' ),
							'narrowed' => __( 'Narrowed', 'blank' ),
						],
					],
					'enable_responsive' => [
						'label'   => __( 'Enable responsive', 'blank' ),
						'default' => true,
						'type'    => 'checkbox',
					],
				],
			],
		],
	],
	'foo_1' => [
		'title'    => __( 'Header Setting', 'blank' ),
		'priority' => 25,
		'sections' => $blank_dummy_section,
	],
	'foo_2' => [
		'title'    => __( 'Content Setting', 'blank' ),
		'priority' => 25,
		'sections' => $blank_dummy_section,
	],
	'foo_3' => [
		'title'    => __( 'Footer Setting', 'blank' ),
		'priority' => 25,
		'sections' => $blank_dummy_section,
	],
] );

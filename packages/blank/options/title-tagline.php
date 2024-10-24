<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

return [
	'settings' => [
		'enable_mobile_logo' => [
			'label'    => __( 'Different Logo for Mobile Devices?', 'blank' ),
			'type'     => 'checkbox',
			'default'  => false,
			'priority' => 8,
		],
		'mobile_logo_image'  => [
			'label'           => __( 'Mobile Logo', 'blank' ),
			'type'            => 'text',
			'default'         => '',
			'priority'        => 8,
			'active_callback' => function () {
				return $this->get_option( 'enable_mobile_logo' );
			},
		],

		'show_site_title'    => [
			'label'    => __( 'Display Site Title', 'blank' ),
			'type'     => 'checkbox',
			'default'  => true,
			'selector' => '.site-title',
		],

		'inline_site_title'  => [
			'label'           => __( 'Inline Logo & Site Title', 'blank' ),
			'type'            => 'checkbox',
			'default'         => true,
			'active_callback' => function () {
				return $this->get_option( 'show_site_title' );
			},
		],

		'show_tagline'       => [
			'label'    => __( 'Display Tagline', 'blank' ),
			'type'     => 'checkbox',
			'default'  => true,
			'selector' => '.site-description',
		],
	],
];

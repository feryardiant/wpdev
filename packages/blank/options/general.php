<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

return [
	'title'       => __( 'General', 'blank' ),
	'description' => __( 'Global theme customization value', 'blank' ),
	'priority'    => 25,
	'sections'    => [
		'layout' => [
			'title'    => __( 'Site Layout', 'blank' ),
			'settings' => [
				'background_color' => [
					'label'   => __( 'Background Color', 'blank' ),
					'default' => '#fff',
					'type'    => 'color',
				],
				'site_layout' => [
					'label'   => __( 'Site Layout', 'blank' ),
					'type'    => 'select',
					'choices' => [
						'none'  => 'Default',
						'boxed' => 'Boxed',
						'wide'  => 'Wide',
					],
					'default' => 'wide',
				],
				'max_site_width' => [
					'label'   => __( 'Maximum Site Width', 'blank' ),
					'type'    => 'number',
					'default' => 1160,
				],
				'enable_responsive' => [
					'label'   => __( 'Enable responsive', 'blank' ),
					'default' => true,
					'type'    => 'checkbox',
				],
			],
		],
		'colors' => [
			'title'    => __( 'Colors', 'blank' ),
			'settings' => [
				'primary_color' => [
					'label'   => __( 'Primary Color', 'blank' ),
					'default' => '#e07503',
					'type'    => 'color',
				],
				'secondary_color' => [
					'label'   => __( 'Secondary Color', 'blank' ),
					'default' => '#6c757d',
					'type'    => 'color',
				],
				'info_color' => [
					'label'   => __( 'Info Color', 'blank' ),
					'default' => '#17a2b8',
					'type'    => 'color',
				],
				'success_color' => [
					'label'   => __( 'Success Color', 'blank' ),
					'default' => '#28a745',
					'type'    => 'color',
				],
				'warning_color' => [
					'label'   => __( 'Warning Color', 'blank' ),
					'default' => '#ffc107',
					'type'    => 'color',
				],
				'danger_color' => [
					'label'   => __( 'Danger Color', 'blank' ),
					'default' => '#dc3545',
					'type'    => 'color',
				],
				'light_color' => [
					'label'   => __( 'Light Color', 'blank' ),
					'default' => '#f8f9fa',
					'type'    => 'color',
				],
				'dark_color' => [
					'label'   => __( 'Dark Color', 'blank' ),
					'default' => '#343a40',
					'type'    => 'color',
				],
			],
		],
	],
];

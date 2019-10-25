<?php
/**
 * Blank Theme.
 *
 * @package  Blank
 * @since    0.2.0
 */

return [
	'title'       => __( 'Typography', 'blank' ),
	'description' => __( 'Global Typography Settings', 'blank' ),
	'settings'    => [
		'typography_base_font' => [
			'label'   => __( 'Base Font Family', 'blank' ),
			'default' => [],
			'type'    => 'typography',
		],
		'typography_base_color' => [
			'label'   => __( 'Base Font Color', 'blank' ),
			'default' => '#0e0f08',
			'type'    => 'color',
		],

		'typography_link_color' => [
			'label'   => __( 'Base Link Color', 'blank' ),
			'default' => '#0e0f08',
			'type'    => 'color',
		],
		'typography_link_hover_color' => [
			'label'   => __( 'Base Link Hover Color', 'blank' ),
			'default' => '#0e0f08',
			'type'    => 'color',
		],

		'typography_heading_font' => [
			'label'   => __( 'Heading Font Family', 'blank' ),
			'default' => [],
			'type'    => 'typography',
		],
		'typography_heading_color' => [
			'label'   => __( 'Heading Font Color', 'blank' ),
			'default' => '#0e0f08',
			'type'    => 'color',
		],

		'typography_blockquote_font' => [
			'label'   => __( 'Blockquote Font', 'blank' ),
			'default' => [],
			'type'    => 'typography',
		],

		'typography_pre_font' => [
			'label'   => __( 'Preformated Font', 'blank' ),
			'default' => [],
			'type'    => 'typography',
		],
	],
];

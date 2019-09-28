<?php
/**
 * Customizer options for handheld nav.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Grid_Parts;

$grid_parts = Grid_Parts::get_instance()->get_parts();

new \Kirki\Section(
	'gridd_mobile',
	[
		'title'    => esc_html__( 'Mobile', 'gridd' ),
		'priority' => 26,
		'panel'    => 'gridd_options',
	]
);

$parts          = Grid_Parts::get_instance()->get_parts();
$sortable_parts = [];
foreach ( $parts as $part ) {
	$sortable_parts[ $part['id'] ] = $part['label'];
}

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_mobile_breakpoint',
		'label'       => esc_html__( 'Grid Mobile Breakpoint', 'gridd' ),
		'description' => esc_html__( 'The threshold below which mobile layouts will be used.', 'gridd' ),
		'section'     => 'gridd_mobile',
		'priority'    => 5,
		'default'     => '992px',
	]
);

Customizer::add_field(
	[
		'type'        => 'sortable',
		'settings'    => 'gridd_grid_load_order',
		'label'       => esc_html__( 'Grid Parts Load Order', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => esc_html__( 'Changes the order in which parts get loaded. This only affects the mobile views and SEO. Important: Your content should always be near the top. You can place secondary items lower in the load order', 'gridd' ),
			]
		),
		'section'     => 'gridd_mobile',
		'default'     => array_keys( $sortable_parts ),
		'priority'    => 900,
		'choices'     => $sortable_parts,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

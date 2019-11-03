<?php
/**
 * Customizer options for handheld nav.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Grid_Parts;

$grid_parts = Grid_Parts::get_instance()->get_parts();

Customizer::add_section(
	'gridd_mobile',
	[
		'title' => esc_html__( 'Mobile', 'gridd' ),
		'panel' => 'theme_settings',
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

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

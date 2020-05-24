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
		'title' => esc_html__( 'Mobile', 'gridd' ),
	]
);

$parts          = Grid_Parts::get_instance()->get_parts();
$sortable_parts = [];
foreach ( $parts as $part ) {
	$sortable_parts[ $part['id'] ] = $part['label'];
}

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_mobile_breakpoint',
		'label'       => esc_html__( 'Grid Mobile Breakpoint', 'gridd' ),
		'description' => esc_html__( 'The threshold below which mobile layouts will be used.', 'gridd' ),
		'section'     => 'gridd_mobile',
		'priority'    => 5,
		'default'     => '992px',
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

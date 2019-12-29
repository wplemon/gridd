<?php
/**
 * Customizer options for handheld nav.
 *
 * @package Gridd
 */

use Gridd\Customizer;

// The parts available for handheld-nav.
$parts = [
	'menu'   => esc_html__( 'Menu', 'gridd' ),
	'home'   => esc_html__( 'Home', 'gridd' ),
	'search' => esc_html__( 'Search', 'gridd' ),
];

new \Kirki\Field\Checkbox(
	[
		'settings'        => 'nav-handheld_enable',
		'label'           => esc_html__( 'Enable Mobile Navigation', 'gridd' ),
		'section'         => 'gridd_mobile',
		'default'         => true,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'nav_handheld_enable_template_enable' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
	]
);

new \Kirki\Field\Sortable(
	[
		'settings'        => 'nav-handheld_parts',
		'label'           => esc_html__( 'Mobile Navigation active parts & order', 'gridd' ),
		'section'         => 'gridd_mobile',
		'default'         => [ 'menu', 'home', 'search' ],
		'choices'         => $parts,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'grid_part_handheld_parts' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback' => [
			[
				'setting'  => 'nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

new \Kirki\Field\Checkbox(
	[
		'settings'        => 'nav-handheld_hide_labels',
		'label'           => esc_attr__( 'Hide Labels', 'gridd' ),
		'description'     => __( 'Enable this option if you want to hide the button labels. Hidden labels only become available to screen-readers.', 'gridd' ),
		'section'         => 'gridd_mobile',
		'default'         => false,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'nav_handheld_hide_labels_rendered' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback' => [
			[
				'setting'  => 'nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

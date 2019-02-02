<?php
/**
 * Customizer options for handheld nav.
 *
 * @package Gridd
 */

use Gridd\Customizer;

gridd_add_customizer_section(
	'gridd_grid_part_details_nav-handheld',
	[
		'title'       => esc_html__( 'Mobile Navigation', 'gridd' ),
		'description' => Customizer::section_description(
			[
				'plus' => [
					esc_html__( 'Additional item: axpandable widget-area with custom icon', 'gridd' ),
				],
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/mobile-navigation/',
			]
		),
		'priority'    => 26,
		'panel'       => 'gridd_options',
	]
);

// The parts available for handheld-nav.
$parts = [
	'menu'   => esc_html__( 'Menu', 'gridd' ),
	'home'   => esc_html__( 'Home', 'gridd' ),
	'search' => esc_html__( 'Search', 'gridd' ),
];

gridd_add_customizer_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_grid_nav-handheld_enable',
		'label'           => esc_html__( 'Enable Mobile Navigation', 'gridd' ),
		'description'     => esc_html__( 'Enables the mobile navigation for devices smaller than the breakpoint defined in your grid settings.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
		'default'         => true,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_nav-handheld_enable_template' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'sortable',
		'settings'        => 'gridd_grid_nav-handheld_parts',
		'label'           => esc_html__( 'Mobile Navigation active parts & order', 'gridd' ),
		'description'     => esc_html__( 'Enable and disable parts of the mobile navigation, and reorder them at will.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
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
				'setting'  => 'gridd_grid_nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_grid_nav-handheld_hide_labels',
		'label'           => esc_attr__( 'Hide Labels', 'gridd' ),
		'tooltip'         => __( 'Enable this option if you want to hide the button labels. If labels are hidden, they only become available to screen-readers.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
		'default'         => false,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_nav_handheld_hide_labels_rendered' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

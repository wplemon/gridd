<?php
/**
 * Customizer Easy Digital Downloads Options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

// Early exit if EDD is not active.
if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}

Customizer::add_section(
	'gridd_edd',
	[
		'title'       => esc_html__( 'EDD Grid', 'gridd' ),
		'panel'       => 'gridd_options',
		'description' => '<a href="https://wplemon.github.io/gridd/grid-parts/easy-digital-downloads.html" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings.', 'gridd' ),
		'priority'    => 90,
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_edd_grid_min_col_width',
		'label'       => esc_html__( 'Minimum Column Width', 'gridd' ),
		'description' => esc_html__( 'Define the minimum width that each item in a grid can have. The columns and rows will be auto-calculated using this value.', 'gridd' ),
		'section'     => 'gridd_edd',
		'default'     => 15,
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-edd-grid-min-col-width',
		'choices'     => [
			'min'    => 10,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_edd_archive_grid_gap',
		'label'       => esc_html__( 'Gap', 'gridd' ),
		'description' => esc_html__( 'The gap between grid items.', 'gridd' ),
		'section'     => 'gridd_edd',
		'default'     => 1.5,
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-edd-grid-gap',
		'choices'     => [
			'min'    => 0,
			'max'    => 10,
			'step'   => .1,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_edd_product_grid_inner_padding',
		'label'     => esc_html__( 'Grid Items Inner Padding', 'gridd' ),
		'section'   => 'gridd_edd',
		'default'   => 1.5,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-edd-grid-inner-padding', '$em' ],
		'choices'   => [
			'min'    => 0,
			'max'    => 10,
			'step'   => .1,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'radio',
		'settings'  => 'gridd_edd_product_grid_image_ratio',
		'label'     => esc_html__( 'Grid Items Image Ratio', 'gridd' ),
		'section'   => 'gridd_edd',
		'default'   => 'golden',
		'transport' => 'refresh',
		'choices'   => [
			'1:1'      => esc_html__( '1:1 (Square)', 'gridd' ),
			'4:3'      => esc_html__( '4:3 (Classic)', 'gridd' ),
			'golden'   => esc_html__( 'Golden Ratio', 'gridd' ),
			'original' => esc_html__( 'Original', 'gridd' ),
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

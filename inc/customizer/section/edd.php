<?php
/**
 * Customizer Easy Digital Downloads Options.
 *
 * @package Gridd
 */

if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}

gridd_add_customizer_section(
	'gridd_edd',
	[
		'title'       => esc_html__( 'EDD Grid', 'gridd' ),
		'panel'       => 'gridd_options',
		'description' => '<a href="https://wplemon.com/documentation/gridd/grid-parts/edd/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ),
		'priority'    => 90,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_edd_grid_min_col_width',
		'label'       => esc_html__( 'Minimum Column Width', 'gridd' ),
		'description' => esc_html__( 'Define the minimum width that each item in a grid can have. The columns and rows will be auto-calculated using this value.', 'gridd' ),
		'section'     => 'gridd_edd',
		'default'     => 320,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-edd-grid-min-col-width', '$px' ],
		'choices'     => [
			'min'    => 200,
			'max'    => 600,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_edd_archive_grid_gap',
		'label'       => esc_html__( 'Gap', 'gridd' ),
		'description' => esc_html__( 'The gap between grid items. Use any valid CSS value.', 'gridd' ),
		'section'     => 'gridd_edd',
		'default'     => 20,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-edd-grid-gap', '$px' ],
		'choices'     => [
			'min'    => 0,
			'max'    => 200,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_edd_product_grid_inner_padding',
		'label'     => esc_html__( 'Grid Items Inner Padding', 'gridd' ),
		'section'   => 'gridd_edd',
		'default'   => 20,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-edd-grid-inner-padding', '$px' ],
		'choices'   => [
			'min'    => 0,
			'max'    => 60,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

gridd_add_customizer_field(
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

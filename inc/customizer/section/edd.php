<?php
/**
 * Customizer Easy Digital Downloads Options.
 *
 * @package Gridd
 */

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}

gridd_add_customizer_panel(
	'gridd_edd',
	[
		'title'    => esc_attr__( 'Easy Digital Downloads', 'gridd' ),
		'priority' => 30,
		'panel'    => 'gridd_options',
	]
);

gridd_add_customizer_section(
	'gridd_edd_grid',
	[
		'title'       => esc_attr__( 'Product Grid', 'gridd' ),
		'panel'       => 'gridd_edd',
		'description' => '<a href="https://wplemon.com/documentation/gridd/grid-parts/edd/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ),
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_edd_grid_min_col_width',
		'label'       => esc_attr__( 'Minimum Column Width', 'gridd' ),
		'description' => esc_html__( 'Define the minimum width that each item in a grid can have. The columns and rows will be auto-calculated using this value.', 'gridd' ),
		'section'     => 'gridd_edd_grid',
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
		'label'       => esc_attr__( 'Gap', 'gridd' ),
		'description' => esc_html__( 'The gap between grid items. Use any valid CSS value.', 'gridd' ),
		'section'     => 'gridd_edd_grid',
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
		'label'     => esc_attr__( 'Grid Items Inner Padding', 'gridd' ),
		'section'   => 'gridd_edd_grid',
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

<?php
/**
 * Customizer Grid Options.
 *
 * @package Gridd
 */

use Gridd\Grid_Parts;

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

$grid_parts = Grid_Parts::get_instance()->get_parts();

gridd_add_customizer_section(
	'gridd_grid',
	[
		'title'       => esc_html__( 'Grid', 'gridd' ),
		'priority'    => 22,
		'description' => sprintf(
			'<div class="gridd-section-description">%1$s%2$s</div>',
			( ! Gridd::is_plus_active() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for a separate grid for mobile devices.', 'gridd' ) . '</div>' : '',
			'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
		),
		'panel'       => 'gridd_options',
	]
);

gridd_add_customizer_field(
	[
		'settings'          => 'gridd_grid',
		'section'           => 'gridd_grid',
		'type'              => 'gridd_grid',
		'grid-part'         => false,
		'label'             => esc_html__( 'Grid Settings', 'gridd' ),
		'description'       => __( 'Edit settings for the grid. For more information and documentation on how the grid works, please read <a href="https://wplemon.com/documentation/gridd/the-grid-control/" target="_blank">this article</a>.', 'gridd' ),
		'default'           => gridd_get_grid_default_value(),
		'sanitize_callback' => [ gridd()->customizer, 'sanitize_gridd_grid' ],
		'choices'           => [
			'parts'     => Grid_Parts::get_instance()->get_parts(),
			'duplicate' => 'gridd_grid_mobile',
		],
		'active_callback'   => [
			[
				'setting'  => 'gridd_global_grid_toggle',
				'operator' => '===',
				'value'    => 'desktop',
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_mobile_breakpoint',
		'label'       => esc_html__( 'Mobile Breakpoint', 'gridd' ),
		'description' => esc_html__( 'The breakpoint that separates mobile views from desktop views. Use a valid CSS unit.', 'gridd' ),
		'tooltip'     => __( 'Screen sizes below the breakpoint defined will get a stacked view instead of grid.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '800px',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_gap',
		'label'       => esc_html__( 'Grid Container Gap', 'gridd' ),
		'description' => esc_html__( 'Adds a gap between your grid-parts, both horizontally and vertically.', 'gridd' ),
		'tooltip'     => __( 'For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '0',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-site-wrapper',
				'property' => 'grid-gap',
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_max_width',
		'label'       => esc_html__( 'Grid Container max-width', 'gridd' ),
		'description' => esc_html__( 'The maximum width for this grid.', 'gridd' ),
		'tooltip'     => esc_html__( 'By setting the max-width to something other than 100% you can build a boxed layout.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-grid-max-width',
	]
);

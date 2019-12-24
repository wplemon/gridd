<?php
/**
 * Customizer Grid Options.
 *
 * @package Gridd
 */

use Gridd\Grid;
use Gridd\Grid_Parts;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();
$grid_parts   = Grid_Parts::get_instance()->get_parts();

new \Kirki\Section(
	'gridd_grid',
	[
		'title'    => esc_html__( 'Main Grid Layout', 'gridd' ),
		'priority' => -100,
		'panel'    => 'layout_options',
		'type'     => 'expanded',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_grid',
		'section'           => 'gridd_grid',
		'type'              => 'gridd_grid',
		'grid-part'         => false,
		'label'             => esc_html__( 'Global Site Grid', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'details' => __( 'The settings in this control apply to all your pages. You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
			]
		),
		'default'           => Grid::get_grid_default_value(),
		'priority'          => 10,
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'choices'           => [
			'parts'     => Grid_Parts::get_instance()->get_parts(),
			'duplicate' => 'gridd_grid_mobile',
		],
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_grid_gap',
		'label'       => esc_html__( 'Grid Container Gap', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid',
		'default'     => '0',
		'transport'   => 'auto',
		'priority'    => 30,
		'output'      => [
			[
				'element'  => '.gridd-site-wrapper',
				'property' => 'grid-gap',
			],
		],
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_grid_max_width',
		'label'       => esc_html__( 'Site Maximum Width', 'gridd' ),
		'description' => esc_html__( 'Set the value to something other than 100% to use a boxed layout.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '100%',
		'priority'    => 40,
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--mw',
			],
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\Grid_Part\Footer;

$sanitization = new Sanitize();

Customizer::add_section(
	'gridd_grid_part_details_footer',
	[
		'title'    => esc_html__( 'Footer', 'gridd' ),
		'priority' => 26,
		'panel'    => 'gridd_options',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_footer_grid',
		'section'           => 'gridd_grid_part_details_footer',
		'type'              => 'gridd_grid',
		'grid-part'         => 'footer',
		'label'             => esc_html__( 'Footer Grid', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => __( 'You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
			]
		),
		'default'           => Footer::get_grid_defaults(),
		'choices'           => [
			'parts' => Footer::get_footer_grid_parts(),
		],
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'priority'          => 10,
	]
);

Customizer::add_field(
	[
		'type'      => 'dimension',
		'settings'  => 'gridd_grid_footer_max_width',
		'label'     => esc_html__( 'Footer Maximum Width', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer',
		'default'   => '100%',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--mw',
			],
		],
		'priority'  => 20,
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_grid_gap',
		'label'       => esc_html__( 'Grid Gap', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '0',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--gg',
			],
		],
		'priority'    => 30,
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => esc_html__( 'Inner padding for all parts in the footer.', 'gridd' ) . ' ' . __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '1em',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--pd',
			],
		],
		'priority'    => 40,
	]
);

Customizer::add_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_footer_background_color',
		'label'       => esc_html__( 'Background Color', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => esc_html__( 'Individual grid-parts can override this by setting their own background color for their area. If you are using a grid-gap the color defined here will be visible between grid-parts. If the color you have selected here is not visible, individual grid-parts may be using a solid background color.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '#ffffff',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bg',
			],
		],
		'choices'     => [
			'alpha' => true,
		],
		'priority'    => 50,
	]
);

Customizer::add_field(
	[
		'type'        => 'checkbox',
		'settings'    => 'gridd_grid_part_details_footer_parts_background_override',
		'label'       => esc_html__( 'Override Footer Parts Background', 'gridd' ),
		'description' => esc_html__( 'Enable this option to force-override the background color of all grid-parts in your footer.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => false,
		'priority'    => 60,
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_footer_border_top_width',
		'label'     => esc_html__( 'Border-Top Width', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer',
		'default'   => 1,
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-w',
			],
		],
		'priority'  => 50,
		'choices'   => [
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'px',
		],
		'priority'  => 70,
	]
);

Customizer::add_field(
	[
		'type'            => 'color',
		'settings'        => 'gridd_grid_footer_border_top_color',
		'label'           => esc_html__( 'Top Border Color', 'gridd' ),
		'section'         => 'gridd_grid_part_details_footer',
		'default'         => 'rgba(0,0,0,.1)',
		'priority'        => 60,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-cl',
			],
		],
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_footer_border_top_width',
				'operator' => '!=',
				'value'    => 0,
			],
		],
		'priority'        => 80,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

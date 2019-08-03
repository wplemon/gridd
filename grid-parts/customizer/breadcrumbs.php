<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

Customizer::add_outer_section(
	'gridd_grid_part_details_breadcrumbs',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Breadcrumbs', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_breadcrumbs',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
					esc_html__( 'Adjustable spacing between breadcrumbs.', 'gridd' ),
				],
				'docs' => 'https://wplemon.github.io/gridd/grid-parts/breadcrumbs.html',
			]
		),
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_breadcrumbs_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => esc_html__( 'Use any valid CSS value. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '1em',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-breadcrumbs-padding',
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_breadcrumbs_max_width',
		'label'       => esc_html__( 'Breadcrumbs Maximum Width', 'gridd' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '',
		'css_vars'    => '--gridd-breadcrumbs-max-width',
		'transport'   => 'postMessage',
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_breadcrumbs_font_size',
		'label'     => esc_html__( 'Font Size', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => esc_html__( 'Controls the font-size for your breadcrumbs. This value is relative to the body font-size, so a value of 1em will have the same size as your content.', 'gridd' ),
			]
		),
		'section'   => 'gridd_grid_part_details_breadcrumbs',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-breadcrumbs-font-size',
		'choices'   => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_breadcrumbs_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_breadcrumbs',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-breadcrumbs-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_grid_breadcrumbs_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'gridd_grid_part_details_breadcrumbs',
		'css_vars'          => '--gridd-breadcrumbs-color',
		'default'           => '#000000',
		'transport'         => 'postMessage',
		'choices'           => [
			'setting' => 'gridd_grid_breadcrumbs_background_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_breadcrumbs_text_align',
		'label'             => esc_html__( 'Alignment', 'gridd' ),
		'section'           => 'gridd_grid_part_details_breadcrumbs',
		'default'           => 'center',
		'transport'         => 'postMessage',
		'css_vars'          => '--gridd-breadcrumbs-text-align',
		'choices'           => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'left' !== $value && 'right' !== $value && 'center' !== $value ) ? 'center' : $value;
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

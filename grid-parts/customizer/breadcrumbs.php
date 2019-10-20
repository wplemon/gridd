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
	'grid_part_details_breadcrumbs',
	[
		'title' => esc_html__( 'Breadcrumbs', 'gridd' ),
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'gridd_grid_breadcrumbs_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_breadcrumbs',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-breadcrumbs',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'formComponent' => 'TwitterPicker',
			'colors'        => [ '#FFFFFF', '#fffcea', '#F9F9F9', '#f7f6e3', '#f7f7f7', '#f4f4e1', '#1A1A1A', '#000000', '#FF6900', '#FCB900', '#7BDCB5', '#00D084', '#8ED1FC', '#0693E3', '#ABB8C3', '#EB144C', '#F78DA7', '#9900EF' ],
		],
		'priority'  => 10,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_grid_breadcrumbs_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => sprintf(
					/* translators: Link properties. */
					__( 'Use any valid CSS value. For details on how padding works, please refer to <a %s>this article</a>.', 'gridd' ),
					'href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow"'
				),
			]
		),
		'section'     => 'grid_part_details_breadcrumbs',
		'default'     => '1em',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-breadcrumbs',
				'property' => '--pd',
			],
		],
		'priority'    => 20,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'  => 'gridd_grid_breadcrumbs_max_width',
		'label'     => esc_html__( 'Breadcrumbs Maximum Width', 'gridd' ),
		'section'   => 'grid_part_details_breadcrumbs',
		'default'   => '100%',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-breadcrumbs',
				'property' => '--mw',
			],
		],
		'priority'  => 30,
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'gridd_grid_breadcrumbs_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'grid_part_details_breadcrumbs',
		'default'           => '#000000',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-breadcrumbs',
				'property' => '--cl',
			],
		],
		'choices'           => [
			'backgroundColor' => 'gridd_grid_breadcrumbs_background_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_breadcrumbs_text_align',
		'label'             => esc_html__( 'Alignment', 'gridd' ),
		'section'           => 'grid_part_details_breadcrumbs',
		'default'           => 'center',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-breadcrumbs',
				'property' => '--ta',
			],
		],
		'choices'           => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'left' !== $value && 'right' !== $value && 'center' !== $value ) ? 'center' : $value;
		},
		'priority'          => 40,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

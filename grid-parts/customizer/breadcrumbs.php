<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

gridd_add_customizer_outer_section(
	'gridd_grid_part_details_breadcrumbs',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Breadcrumbs', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_breadcrumbs',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
					esc_html__( 'Adjustable pacing between breadcrumbs.', 'gridd' ),
				],
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/breadcrumbs/',
			]
		),
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'text',
		'settings'    => 'gridd_grid_breadcrumbs_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Inner padding for this grid-part. Use any valid CSS value.', 'gridd' ),
		'tooltip'     => __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '1em',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-breadcrumbs-padding',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_breadcrumbs_max_width',
		'label'       => esc_html__( 'Max-Width', 'gridd' ),
		'description' => esc_html__( 'The maximum width that the contents of this grid-part can use.', 'gridd' ),
		'tooltip'     => __( 'Use any valid CSS value like <code>50em</code>, <code>800px</code> or <code>100%</code>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '',
		'css_vars'    => '--gridd-breadcrumbs-max-width',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_breadcrumbs_font_size',
		'label'     => esc_html__( 'Font Size', 'gridd' ),
		'tooltip'   => esc_html__( 'Controls the font-size for your breadcrumbs. This value is relevant to the body font-size, so a value of 1em will have the same size as your content.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_breadcrumbs',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-breadcrumbs-font-size', '$em' ],
		'choices'   => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
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

gridd_add_customizer_field(
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

gridd_add_customizer_field(
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
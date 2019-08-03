<?php
/**
 * Customizer Typography Options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

/**
 * Add the Theme-Options panel.
 */
Customizer::add_section(
	'gridd_typography',
	[
		'title'       => esc_html__( 'Typography', 'gridd' ),
		'description' => Customizer::section_description(
			'gridd_typography',
			[
				'docs' => 'https://wplemon.github.io/gridd/customizer-sections/typography.html',
			]
		),
		'priority'    => 20,
		'panel'       => 'gridd_options',
	]
);

/**
 * Body typography.
 */
Customizer::add_field(
	[
		'type'      => 'typography',
		'settings'  => 'gridd_body_typography',
		'label'     => esc_html__( 'Body Typography', 'gridd' ),
		'section'   => 'gridd_typography',
		'priority'  => 10,
		'default'   => [
			'font-family' => 'sans-serif',
			'font-weight' => 400,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element' => 'body',
			],
			[
				'element' => '.edit-post-visual-editor.editor-styles-wrapper',
				'context' => [ 'editor' ],
			],
		],
		'choices'   => [
			'fonts' => [
				'google' => [ 'popularity' ],
			],
		],
	]
);

/**
 * Headers typography.
 */
Customizer::add_field(
	[
		'type'      => 'typography',
		'settings'  => 'gridd_headers_typography',
		'label'     => esc_html__( 'Headers Typography', 'gridd' ),
		'section'   => 'gridd_typography',
		'priority'  => 20,
		'default'   => [
			'font-family' => 'sans-serif',
			'variant'     => 700,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element' => 'h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.site-title',
			],
			[
				'context' => [ 'editor' ],
				'element' => [
					'.editor-post-title__block .editor-post-title__input',
					'.wp-block-heading h1',
					'.wp-block-heading h2',
					'.wp-block-heading h3',
					'.wp-block-heading h4',
					'.wp-block-heading h5',
					'.wp-block-heading h6',
				],
			],
		],
		'choices'   => [
			'fonts' => [
				'google' => [ 'popularity' ],
			],
		],
	]
);

/**
 * Body typography.
 */
Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_body_font_size',
		'label'       => esc_html__( 'Body Font-Size', 'gridd' ),
		'description' => esc_html__( 'We recommend you a font-size greater than 18px to ensure greater readability.', 'gridd' ),
		'section'     => 'gridd_typography',
		'default'     => 18,
		'priority'    => 60,
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-font-size',
		'choices'     => [
			'min'    => 13,
			'max'    => 40,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_fluid_typography_ratio',
		'label'       => esc_html__( 'Fluid typography Ratio', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => sprintf(
					/* Translators: link attributes. */
					__( 'Defines by how much your font-size will change depending on the screen-size. Larger values will increase the font-size more on bigger screens. Set to 0 if you do not want the font-size to change depending on the screen-size. Need more Information? <a %s>Read this article</a>', 'gridd' ),
					'href="https://wplemon.github.io/gridd/fluid-typography.html" target="_blank"'
				),
			]
		),
		'section'     => 'gridd_typography',
		'default'     => 0.25,
		'priority'    => 70,
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-typo-ratio',
		'choices'     => [
			'min'  => 0,
			'max'  => 1,
			'step' => .001,
		],
	]
);

/**
 * Type Scale
 */
Customizer::add_field(
	[
		'settings'          => 'gridd_type_scale',
		'type'              => 'radio',
		'label'             => esc_attr__( 'Typography Scale', 'gridd' ),
		'description'       => esc_attr__( 'Controls the size relations between your headers and your main typography font-size.', 'gridd' ),
		'section'           => 'gridd_typography',
		'default'           => '1.26',
		'transport'         => 'postMessage',
		'css_vars'          => '--gridd-typo-scale',
		'priority'          => 80,
		'choices'           => [
			/* Translators: Numeric representation of the scale. */
			'1.149' => sprintf( esc_attr__( '%s - Musical Pentatonic (classic)', 'gridd' ), '1.149' ),
			/* Translators: Numeric representation of the scale. */
			'1.26'  => sprintf( esc_attr__( '%s - Musical Tritonic', 'gridd' ), '1.26' ),
			/* Translators: Numeric representation of the scale. */
			'1.333' => sprintf( esc_attr__( '%s - Perfect Fourth', 'gridd' ), '1.333' ),
		],
		'priority'          => 80,
		'sanitize_callback' => function( $value ) {
			return is_numeric( $value ) ? $value : '1.26';
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

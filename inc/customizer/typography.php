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
		'title'    => esc_html__( 'Typography', 'gridd' ),
		'priority' => 20,
		'panel'    => 'gridd_options',
	]
);

/**
 * Body typography switch.
 */
Customizer::add_field(
	[
		'type'     => 'checkbox',
		'settings' => 'custom_body_typography',
		'label'    => esc_html__( 'Custom Body Typography', 'gridd' ),
		'section'  => 'gridd_typography',
		'priority' => 9,
		'default'  => false,
	]
);

/**
 * Body typography.
 */
Customizer::add_field(
	[
		'type'            => 'typography',
		'settings'        => 'gridd_body_typography',
		'label'           => esc_html__( 'Body Typography', 'gridd' ),
		'section'         => 'gridd_typography',
		'priority'        => 10,
		'default'         => [
			'font-family' => '',
			'font-weight' => 400,
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element' => 'body:not(.gridd-has-system-body-typography)',
			],
			get_theme_mod( 'disable_editor_styles' ) ? [] : [
				'element' => '.edit-post-visual-editor.editor-styles-wrapper',
				'context' => [ 'editor' ],
			],
		],
		'choices'         => [
			'fonts' => [
				'google'   => [ 'popularity' ],
				'standard' => [],
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'custom_body_typography' );
		},
	]
);

/**
 * Body typography switch.
 */
Customizer::add_field(
	[
		'type'     => 'checkbox',
		'settings' => 'custom_headers_typography',
		'label'    => esc_html__( 'Custom Headers Typography', 'gridd' ),
		'section'  => 'gridd_typography',
		'priority' => 19,
		'default'  => false,
	]
);

/**
 * Headers typography.
 */
Customizer::add_field(
	[
		'type'            => 'typography',
		'settings'        => 'gridd_headers_typography',
		'label'           => esc_html__( 'Headers Typography', 'gridd' ),
		'section'         => 'gridd_typography',
		'priority'        => 20,
		'default'         => [
			'font-family' => '',
			'variant'     => 700,
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element' => [
					'body:not(.gridd-has-system-headers-typography) h1',
					'body:not(.gridd-has-system-headers-typography) h2',
					'body:not(.gridd-has-system-headers-typography) h3',
					'body:not(.gridd-has-system-headers-typography) h4',
					'body:not(.gridd-has-system-headers-typography) h5',
					'body:not(.gridd-has-system-headers-typography) h6',
					'body:not(.gridd-has-system-headers-typography) .h1',
					'body:not(.gridd-has-system-headers-typography) .h2',
					'body:not(.gridd-has-system-headers-typography) .h3',
					'body:not(.gridd-has-system-headers-typography) .h4',
					'body:not(.gridd-has-system-headers-typography) .h5',
					'body:not(.gridd-has-system-headers-typography) .h6',
				],
			],
			get_theme_mod( 'disable_editor_styles' ) ? [] : [
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
		'choices'         => [
			'fonts' => [
				'google'   => [ 'popularity' ],
				'standard' => [],
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'custom_headers_typography' );
		},
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
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--gridd-font-size',
			],
			get_theme_mod( 'disable_editor_styles' ) ? [] : [
				'element'  => '.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--gridd-font-size',
				'context'  => [ 'editor' ],
			],
		],
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
		'label'       => esc_html__( 'Fluid Typography Ratio', 'gridd' ),
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
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--gridd-typo-ratio',
			],
			get_theme_mod( 'disable_editor_styles' ) ? [] : [
				'element'  => '.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--gridd-typo-ratio',
				'context'  => [ 'editor' ],
			],
		],
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
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--gridd-typo-scale',
			],
			get_theme_mod( 'disable_editor_styles' ) ? [] : [
				'element'  => '.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--gridd-typo-scale',
				'context'  => [ 'editor' ],
			],
		],
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

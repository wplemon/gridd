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
		'title'       => esc_html__( 'Typography & Links', 'gridd' ),
		'description' => Customizer::section_description(
			'gridd_typography',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text, headers and links', 'gridd' ),
					esc_html__( 'Adjustable typography scales', 'gridd' ),
					esc_html__( 'Links decoration (underlined/not-underlined) for content and headers separately.', 'gridd' ),
				],
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
		'type'        => 'typography',
		'settings'    => 'gridd_body_typography',
		'label'       => esc_html__( 'Body Typography', 'gridd' ),
		'description' => esc_html__( 'Edit the font-family used for the body of your site. This applies to all text except the headers which have a separate setting.', 'gridd' ),
		'section'     => 'gridd_typography',
		'priority'    => 10,
		'default'     => [
			'font-family' => 'Noto Serif',
			'variant'     => 'regular',
		],
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => 'body',
			],
			[
				'element' => '.edit-post-visual-editor.editor-styles-wrapper',
				'context' => [ 'editor' ],
			],
		],
		'choices'     => [
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
		'type'        => 'typography',
		'settings'    => 'gridd_headers_typography',
		'label'       => esc_html__( 'Headers Typography', 'gridd' ),
		'description' => esc_html__( 'Edit the font-family used for all headers on your site.', 'gridd' ),
		'section'     => 'gridd_typography',
		'priority'    => 20,
		'default'     => [
			'font-family' => 'Noto Serif',
			'variant'     => '700',
		],
		'transport'   => 'auto',
		'output'      => [
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
		'choices'     => [
			'fonts' => [
				'google' => [ 'popularity' ],
			],
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'description'       => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
		'section'           => 'gridd_typography',
		'priority'          => 30,
		'default'           => '#000000',
		'css_vars'          => '--gridd-text-color',
		'transport'         => 'postMessage',
		'choices'           => [
			'setting' => 'gridd_grid_content_background_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_links_color',
		'type'              => 'gridd-wcag-lc',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'description'       => esc_html__( 'Select the hue for you links. The color will be auto-calculated to ensure maximum readability according to WCAG.', 'gridd' ),
		'section'           => 'gridd_typography',
		'transport'         => 'postMessage',
		'priority'          => 40,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#0f5e97',
		'choices'           => [
			'backgroundColor' => 'gridd_grid_content_background_color',
			'textColor'       => 'gridd_text_color',
		],
		'css_vars'          => '--gridd-links-color',
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_links_hover_color',
		'type'              => 'gridd-wcag-lc',
		'label'             => esc_html__( 'Links Hover Color', 'gridd' ),
		'section'           => 'gridd_typography',
		'transport'         => 'postMessage',
		'priority'          => 50,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#541cfc',
		'css_vars'          => '--gridd-links-hover-color',
		'choices'           => [
			'backgroundColor' => 'gridd_grid_content_background_color',
			'textColor'       => 'gridd_text_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
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
		'description' => esc_html__( 'Choose the main font-size for your content. We recommend you a font-size greater than 18px to ensure greater readability.', 'gridd' ),
		'section'     => 'gridd_typography',
		'default'     => 18,
		'priority'    => 60,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-font-size', '$px' ],
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
		'description' => esc_html__( 'Defines by how much your font-size will change depending on the screen-size. Larger values will increase the font-size more on bigger screens. Set to 0 if you do not want the font-size to change depending on the screen-size.', 'gridd' ),
		/* translators: "Read this article" link. */
		'tooltip'     => sprintf( esc_html__( 'Need more Information? %s', 'gridd' ), '<a href="https://wplemon.github.io/gridd/fluid-typography.html" target="_blank">' . esc_html__( 'Read this article.', 'gridd' ) ),
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

<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

new \Kirki\Section(
	'breadcrumbs',
	[
		'panel'           => 'theme_options',
		'title'           => esc_html__( 'Breadcrumbs', 'gridd' ),
		'type'            => 'kirki-expanded',
		'priority'        => 50,
		'active_callback' => function() {
			return \Gridd\Customizer::is_section_active_part( 'breadcrumbs' );
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'breadcrumbs_custom_options',
		'section'   => 'breadcrumbs',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'gridd_grid_breadcrumbs_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'breadcrumbs',
		'default'         => '#ffffff',
		'transport'       => 'postMessage',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-breadcrumbs.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => function() {
			return get_theme_mod( 'breadcrumbs_custom_options', false );
		},
		'priority'        => 10,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'gridd_grid_breadcrumbs_padding',
		'label'           => esc_html__( 'Padding', 'gridd' ),
		'description'     => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => sprintf(
					/* translators: Link properties. */
					__( 'Use any valid CSS value. For details on how padding works, please refer to <a %s>this article</a>.', 'gridd' ),
					'href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow"'
				),
			]
		),
		'section'         => 'breadcrumbs',
		'default'         => '1em',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-breadcrumbs.custom-options',
				'property' => '--pd',
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'breadcrumbs_custom_options', false );
		},
		'priority'        => 20,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'gridd_grid_breadcrumbs_max_width',
		'label'           => esc_html__( 'Breadcrumbs Maximum Width', 'gridd' ),
		'section'         => 'breadcrumbs',
		'default'         => '100%',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-breadcrumbs.custom-options',
				'property' => '--mw',
			],
		],
		'priority'        => 30,
		'active_callback' => function() {
			return get_theme_mod( 'breadcrumbs_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'gridd_grid_breadcrumbs_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'breadcrumbs',
		'default'           => '#000000',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-breadcrumbs.custom-options',
				'property' => '--cl',
			],
		],
		'choices'           => [
			'backgroundColor' => 'gridd_grid_breadcrumbs_background_color',
			'appearance'      => 'hidden',
		],
		'active_callback'   => function() {
			return get_theme_mod( 'breadcrumbs_custom_options', false );
		},
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_breadcrumbs_text_align',
		'label'             => esc_html__( 'Alignment', 'gridd' ),
		'section'           => 'breadcrumbs',
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

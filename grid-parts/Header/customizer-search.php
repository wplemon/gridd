<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

new \Kirki\Section(
	'header_search',
	[
		'title'           => esc_html__( 'Header Search', 'gridd' ),
		'panel'           => 'theme_options',
		'type'            => 'kirki-expanded',
		'priority'        => 40,
		'active_callback' => function() {
			return \Gridd\Customizer::is_section_active_part( 'header_search' );
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'header_search_custom_options',
		'section'   => 'header_search',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'  => 'header_search_mode',
		'label'     => esc_html__( 'Search Mode', 'gridd' ),
		'section'   => 'header_search',
		'default'   => 'form',
		'transport' => 'refresh',
		'choices'   => [
			'form'     => esc_html__( 'Form', 'gridd' ),
			'slide-up' => esc_html__( 'Slide Up', 'gridd' ),
		],
		'priority'  => 10,
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'header_search_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'header_search',
		'default'         => '#ffffff',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_search.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 20,
		'active_callback' => function() {
			return get_theme_mod( 'header_search_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'        => 'header_search_color',
		'label'           => esc_html__( 'Text Color', 'gridd' ),
		'section'         => 'header_search',
		'default'         => '#000000',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_search.custom-options',
				'property' => '--cl',
			],
		],
		'choices'         => [
			'backgroundColor' => 'header_search_background_color',
		],
		'priority'        => 30,
		'active_callback' => function() {
			return get_theme_mod( 'header_search_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'header_search_padding',
		'label'           => esc_html__( 'Horizontal Padding', 'gridd' ),
		'section'         => 'header_search',
		'default'         => 1,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'       => '.gridd-tp-header_search.custom-options',
				'property'      => '--pd',
				'value_pattern' => '$em',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'priority'        => 40,
		'active_callback' => function() {
			return get_theme_mod( 'header_search_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'header_search_font_size',
		'label'           => esc_html__( 'Font Size', 'gridd' ),
		'description'     => esc_html__( 'The value selected here is relative to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'         => 'header_search',
		'default'         => 1,
		'output'          => [
			[
				'element'  => '.gridd-tp-header_search.custom-options',
				'property' => '--fsr',
			],
		],
		'choices'         => [
			'min'    => .5,
			'max'    => 4,
			'step'   => .01,
			'suffix' => 'em',
		],
		'transport'       => 'auto',
		'priority'        => 50,
		'active_callback' => function() {
			return get_theme_mod( 'header_search_custom_options', false );
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

Customizer::add_outer_section(
	'grid_part_details_header_contact_info',
	[
		'title' => esc_html__( 'Header Contact Info', 'gridd' ),
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'header_contact_info_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_header_contact_info',
		'default'   => '#ffffff',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-header_contact_info',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'  => 20,
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'settings'          => 'header_contact_info_text_color',
		'section'           => 'grid_part_details_header_contact_info',
		'choices'           => [
			'backgroundColor' => 'header_contact_info_background_color',
			'appearance'      => 'hidden',
		],
		'default'           => '#000000',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header_contact_info',
				'property' => '--cl',
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'priority'          => 30,
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'header_contact_info_font_size',
		'label'       => esc_html__( 'Font Size', 'gridd' ),
		'description' => esc_html__( 'The value selected here is relative to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'     => 'grid_part_details_header_contact_info',
		'default'     => .85,
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-header_contact_info',
				'property' => '--fs',
			],
		],
		'choices'     => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
		'priority'    => 40,
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'header_contact_info_padding',
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
		'section'     => 'grid_part_details_header_contact_info',
		'default'     => '1em',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-header_contact_info',
				'property' => '--pd',
			],
		],
		'priority'    => 50,
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'header_contact_text_align',
		'label'             => esc_html__( 'Text Align', 'gridd' ),
		'section'           => 'grid_part_details_header_contact_info',
		'default'           => 'flex-start',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header_contact_info',
				'property' => '--ta',
			],
		],
		'choices'           => [
			'flex-start' => esc_html__( 'Left', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'flex-start' !== $value && 'flex-end' !== $value && 'center' !== $value ) ? 'flex-start' : $value;
		},
		'priority'          => 60,
	]
);

Customizer::add_field(
	[
		'type'              => 'code',
		'settings'          => 'grid_part_details_header_contact_info',
		'label'             => esc_html__( 'Content', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'details' => esc_html__( 'Enter any text you want - usually your contact info or important announcements that you want your visitors to see.', 'gridd' ),
			]
		),
		'section'           => 'grid_part_details_header_contact_info',
		'default'           => __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ),
		'transport'         => 'postMessage',
		'js_vars'           => [
			[
				'element'  => '.gridd-tp-header_contact_info.gridd-tp',
				'function' => 'html',
			],
		],
		'choices'           => [
			'language' => 'html',
		],
		'sanitize_callback' => 'wp_kses_post',
		'priority'          => 10,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
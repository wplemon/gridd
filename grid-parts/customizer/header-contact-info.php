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

new \Kirki\Section(
	'gridd_grid_part_details_header_contact_info',
	[
		/* translators: The grid-part label. */
		'title' => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Contact Info', 'gridd' ) ),
		'type'  => 'kirki-outer',
	]
);

Customizer::add_field(
	[
		'type'              => 'editor',
		'settings'          => 'gridd_grid_part_details_header_contact_info',
		'label'             => esc_html__( 'Content', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'details' => esc_html__( 'Enter any text you want - usually your contact info or important announcements that you want your visitors to see.', 'gridd' ),
			]
		),
		'section'           => 'gridd_grid_part_details_header_contact_info',
		'default'           => __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ),
		'transport'         => 'postMessage',
		'js_vars'           => [
			[
				'element'  => '.gridd-tp-header_contact_info.gridd-tp',
				'function' => 'html',
			],
		],
		'sanitize_callback' => 'wp_kses_post',
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_contact_info_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_contact_info',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--h-cnt-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'settings'          => 'gridd_grid_part_details_header_contact_info_text_color',
		'section'           => 'gridd_grid_part_details_header_contact_info',
		'choices'           => [
			'setting' => 'gridd_grid_part_details_header_contact_info_background_color',
		],
		'default'           => '#000000',
		'transport'         => 'postMessage',
		'css_vars'          => '--h-cnt-cl',
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_part_details_header_contact_info_font_size',
		'label'       => esc_html__( 'Font Size', 'gridd' ),
		'description' => esc_html__( 'The value selected here is relative to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => .85,
		'transport'   => 'postMessage',
		'css_vars'    => '--h-cnt-fs',
		'choices'     => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

new \Kirki\Field\Dimension(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_part_details_header_contact_info_padding',
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
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => '10px',
		'transport'   => 'postMessage',
		'css_vars'    => '--h-cnt-pd',
	]
);

Customizer::add_field(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_part_details_header_contact_text_align',
		'label'             => esc_html__( 'Text Align', 'gridd' ),
		'section'           => 'gridd_grid_part_details_header_contact_info',
		'default'           => 'flex-start',
		'transport'         => 'postMessage',
		'css_vars'          => '--h-cnt-ta',
		'choices'           => [
			'flex-start' => esc_html__( 'Left', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'flex-start' !== $value && 'flex-end' !== $value && 'center' !== $value ) ? 'flex-start' : $value;
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

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
	'gridd_grid_part_details_header_contact_info',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Contact Info', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_header_contact_info',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
				],
				'docs' => 'https://wplemon.github.io/gridd/grid-parts/contact-information.html',
			]
		),
	]
);

Customizer::add_field(
	[
		'type'              => 'editor',
		'settings'          => 'gridd_grid_part_details_header_contact_info',
		'label'             => esc_html__( 'Content', 'gridd' ),
		'tooltip'           => esc_html__( 'Enter any text you want - usually your contact info or important announcements that you want your visitors to see.', 'gridd' ),
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
		'css_vars'  => '--gridd-header-contact-bg',
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
		'css_vars'          => '--gridd-header-contact-color',
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_part_details_header_contact_info_font_size',
		'label'     => esc_html__( 'Font Size', 'gridd' ),
		'tooltip'   => esc_html__( 'The value selected here is relevant to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_contact_info',
		'default'   => .85,
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-contact-font-size',
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
		'type'      => 'dimension',
		'settings'  => 'gridd_grid_part_details_header_contact_info_padding',
		'label'     => esc_html__( 'Padding', 'gridd' ),
		'tooltip'   => __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_contact_info',
		'default'   => '10px',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-contact-padding',
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
		'css_vars'          => '--gridd-header-contact-text-align',
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

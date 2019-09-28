<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Theme;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

// Add section.
new \Kirki\Section(
	'gridd_grid_part_details_footer_copyright',
	[
		/* translators: The grid-part label. */
		'title' => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Copyright Area', 'gridd' ) ),
		'type'  => 'kirki-outer',
	]
);

Customizer::add_field(
	[
		'type'              => 'editor',
		'settings'          => 'gridd_copyright_text',
		'label'             => esc_html__( 'Copyright Text', 'gridd' ),
		'description'       => esc_html__( 'The text for your copyright area (accepts HTML).', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_copyright',
		/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
		'default'           => sprintf( __( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ), '<a href="https://wordpress.org/">WordPress</a>', 'Gridd', '<a href="https://wplemon.com/" rel="nofollow">wplemon.com</a>' ),
		'transport'         => 'postMessage',
		'choices'           => [
			'language' => 'html',
		],
		'priority'          => 10,
		'sanitize_callback' => 'wp_kses_post',
		'partial_refresh'   => [
			'gridd_the_grid_part_footer_copyright_template' => [
				'selector'            => '.gridd-tp-footer_copyright',
				'container_inclusive' => false,
				'render_callback'     => function() {
					Theme::get_template_part( 'grid-parts/templates/footer-copyright' );
				},
			],
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_footer_copyright_bg_color',
		'label'     => esc_html__( 'Copyright area background-color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--ft-cp-bg',
		'priority'  => 110,
		'choices'   => [
			'alpha' => true,
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_grid_footer_copyright_color',
		'label'             => esc_html__( 'Copyright Text Color', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_copyright',
		'default'           => '#000000',
		'transport'         => 'postMessage',
		'css_vars'          => '--ft-cp-cl',
		'priority'          => 120,
		'choices'           => [
			'setting' => 'gridd_grid_footer_copyright_bg_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-lc',
		'settings'          => 'gridd_grid_footer_copyright_links_color',
		'label'             => esc_html__( 'Copyright Links Color', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_copyright',
		'default'           => '#0f5e97',
		'transport'         => 'postMessage',
		'css_vars'          => '--ft-cp-lc',
		'priority'          => 130,
		'choices'           => [
			'backgroundColor' => 'gridd_grid_footer_copyright_bg_color',
			'textColor'       => 'gridd_grid_footer_copyright_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_footer_copyright_text_font_size',
		'label'       => esc_html__( 'Font Size', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => esc_html__( 'The font-size defined here is relative to the body font-size so a size of 1em will be the same ssize as your content.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_footer_copyright',
		'default'     => 1,
		'transport'   => 'postMessage',
		'css_vars'    => '--ft-cp-fs',
		'priority'    => 140,
		'choices'     => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

new \Kirki\Field\Radio_Buttonset(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_footer_copyright_text_align',
		'label'             => esc_html__( 'Text Alignment', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_copyright',
		'default'           => 'center',
		'transport'         => 'postMessage',
		'css_vars'          => '--ft-cp-ta',
		'priority'          => 150,
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

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

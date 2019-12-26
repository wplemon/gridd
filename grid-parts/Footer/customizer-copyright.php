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
	'grid_part_details_footer_copyright',
	[
		'title'           => esc_html__( 'Copyright Area', 'gridd' ),
		'priority'        => 90,
		'type'            => 'kirki-expanded',
		'panel'           => 'theme_options',
		'active_callback' => function() {
			return \Gridd\Customizer::is_section_active_part( 'footer_copyright' );
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'footer_copyright_custom_options',
		'section'   => 'grid_part_details_footer_copyright',
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
		'settings'        => 'gridd_grid_footer_copyright_bg_color',
		'label'           => esc_html__( 'Copyright area background-color', 'gridd' ),
		'section'         => 'grid_part_details_footer_copyright',
		'default'         => '#ffffff',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_copyright.custom-options',
				'property' => '--bg',
			],
		],
		'priority'        => 10,
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => function() {
			return get_theme_mod( 'footer_copyright_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'gridd_grid_footer_copyright_color',
		'label'             => esc_html__( 'Copyright Text Color', 'gridd' ),
		'section'           => 'grid_part_details_footer_copyright',
		'default'           => '#000000',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-footer_copyright.custom-options',
				'property' => '--cl',
			],
		],
		'priority'          => 20,
		'choices'           => [
			'backgroundColor' => 'gridd_grid_footer_copyright_bg_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return get_theme_mod( 'footer_copyright_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'gridd_grid_footer_copyright_text_font_size',
		'label'           => esc_html__( 'Font Size', 'gridd' ),
		'description'     => esc_html__( 'The font-size defined here is relative to the body font-size so a size of 1em will be the same ssize as your content.', 'gridd' ),
		'section'         => 'grid_part_details_footer_copyright',
		'default'         => 1,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_copyright.custom-options',
				'property' => '--fs',
			],
		],
		'priority'        => 40,
		'choices'         => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
		'active_callback' => function() {
			return get_theme_mod( 'footer_copyright_custom_options', false );
		},
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_footer_copyright_text_align',
		'label'             => esc_html__( 'Text Alignment', 'gridd' ),
		'section'           => 'grid_part_details_footer_copyright',
		'default'           => 'center',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-footer_copyright',
				'property' => '--ta',
			],
		],
		'priority'          => 50,
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

new \Kirki\Field\Textarea(
	[
		'settings'          => 'gridd_copyright_text',
		'label'             => esc_html__( 'Copyright Text', 'gridd' ),
		'description'       => esc_html__( 'The text for your copyright area (accepts HTML).', 'gridd' ),
		'section'           => 'grid_part_details_footer_copyright',
		'default'           => sprintf(
			/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
			__( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ),
			'<a href="https://wordpress.org/">WordPress</a>',
			'<a href="https://wplemon.com/gridd">Gridd</a>',
			'<a href="https://wplemon.com/" rel="nofollow">wplemon.com</a>'
		),
		'transport'         => 'postMessage',
		'choices'           => [
			'language' => 'html',
		],
		'priority'          => 60,
		'sanitize_callback' => 'wp_kses_post',
		'partial_refresh'   => [
			'gridd_the_grid_part_footer_copyright_template' => [
				'selector'            => '.gridd-tp-footer_copyright',
				'container_inclusive' => false,
				'render_callback'     => function() {
					Theme::get_template_part( 'grid-parts/Footer/template-copyright' );
				},
			],
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

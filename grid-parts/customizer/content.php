<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

Customizer::add_section(
	'grid_part_details_content',
	[
		/* translators: The grid-part label. */
		'title'    => esc_html__( 'Content', 'gridd' ),
		'priority' => 25,
		'panel'    => 'gridd_options',
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'content_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => '#ffffff',
		'output'    => [
			[
				'element'  => '.gridd-tp-content',
				'property' => '--bg',
			],
		],
		'transport' => 'auto',
		'priority'  => 10,
		'choices'   => [
			'formComponent' => 'TwitterPicker',
			'colors'        => [ '#FFFFFF', '#fffcea', '#F9F9F9', '#f7f6e3', '#f7f7f7', '#f4f4e1', '#1A1A1A', '#000000' ],
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'grid_part_details_content',
		'priority'          => 20,
		'default'           => '#000000',
		'output'            => [
			[
				'element'  => ':root',
				'property' => '--cl',
			],
		],
		'transport'         => 'auto',
		'choices'           => [
			'setting' => 'content_background_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \WPLemon\Field\WCAGLinkColor(
	[
		'settings'          => 'gridd_links_color',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'description'       => esc_html__( 'Select the hue for you links. The color will be auto-calculated to ensure maximum readability according to WCAG.', 'gridd' ),
		'section'           => 'grid_part_details_content',
		'transport'         => 'auto',
		'priority'          => 30,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#0f5e97',
		'choices'           => [
			'backgroundColor' => 'content_background_color',
			'textColor'       => 'gridd_text_color',
			'linksUnderlined' => true,
		],
		'output'            => [
			[
				'element'  => ':root',
				'property' => '--lc',
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

Customizer::add_field(
	[
		'type'      => 'dimension',
		'settings'  => 'content_max_width',
		'label'     => esc_html__( 'Content Maximum Width', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => '45rem',
		'output'    => [
			[
				'element'  => ':root',
				'property' => '--cmw',
			],
		],
		'transport' => 'auto',
		'priority'  => 50,
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'content_padding_horizontal',
		'label'     => esc_html__( 'Horizontal Content Padding', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => 1,
		'output'    => [
			[
				'element'       => '.gridd-tp-content',
				'property'      => '--pd-h',
				'value_pattern' => '$rem',
			],
		],
		'choices'   => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'rem',
		],
		'transport' => 'auto',
		'priority'  => 60,
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'content_padding_top',
		'label'     => esc_html__( 'Top Content Padding', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => 1,
		'output'    => [
			[
				'element'       => '.gridd-tp-content',
				'property'      => '--pd-t',
				'value_pattern' => '$rem',
			],
		],
		'choices'   => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'rem',
		],
		'transport' => 'auto',
		'priority'  => 70,
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'content_padding_bottom',
		'label'     => esc_html__( 'Bottom Content Padding', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => 1,
		'output'    => [
			[
				'element'       => '.gridd-tp-content',
				'property'      => '--pd-b',
				'value_pattern' => '$rem',
			],
		],
		'choices'   => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'rem',
		],
		'transport' => 'auto',
		'priority'  => 80,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

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
	'gridd_grid_part_details_content',
	[
		/* translators: The grid-part label. */
		'title'       => esc_html__( 'Content', 'gridd' ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_content',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text, headers and links', 'gridd' ),
				],
				'docs' => 'https://wplemon.github.io/gridd/grid-parts/content.html',
			]
		),
		'priority'    => 25,
		'panel'       => 'gridd_options',
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_content_max_width',
		'label'       => esc_html__( 'Content Maximum Width', 'gridd' ),
		'section'     => 'gridd_grid_part_details_content',
		'default'     => '45em',
		'css_vars'    => '--gridd-content-max-width',
		'transport'   => 'postMessage',
		'priority'    => 10,
	]
);

Customizer::add_field(
	[
		'type'      => 'dimensions',
		'settings'  => 'gridd_grid_content_padding',
		'label'     => esc_html__( 'Content Padding', 'gridd' ),
		'section'   => 'gridd_grid_part_details_content',
		'default'   => [
			'top'    => '0px',
			'bottom' => '0px',
			'left'   => '20px',
			'right'  => '20px',
		],
		'css_vars'  => [
			[ '--gridd-content-padding-top', '$', 'top' ],
			[ '--gridd-content-padding-bottom', '$', 'bottom' ],
			[ '--gridd-content-padding-left', '$', 'left' ],
			[ '--gridd-content-padding-right', '$', 'right' ],
		],
		'transport' => 'postMessage',
		'priority'  => 15,
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_content_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_content',
		'default'   => '#ffffff',
		'css_vars'  => '--gridd-content-bg',
		'transport' => 'postMessage',
		'priority'  => 30,
		'choices'   => [
			'alpha' => true,
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'gridd_grid_part_details_content',
		'priority'          => 40,
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
		'section'           => 'gridd_grid_part_details_content',
		'transport'         => 'postMessage',
		'priority'          => 50,
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
		'section'           => 'gridd_grid_part_details_content',
		'transport'         => 'postMessage',
		'priority'          => 60,
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

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

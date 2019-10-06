<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Customizer;

Customizer::add_outer_section(
	'grid_part_details_header_search',
	[
		/* translators: The grid-part label. */
		'title' => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Search', 'gridd' ) ),
	]
);

Customizer::add_field(
	[
		'type'      => 'radio-buttonset',
		'settings'  => 'header_search_mode',
		'label'     => esc_html__( 'Search Mode', 'gridd' ),
		'section'   => 'grid_part_details_header_search',
		'default'   => 'form',
		'transport' => 'refresh',
		'choices'   => [
			'form'     => esc_html__( 'Form', 'gridd' ),
			'slide-up' => esc_html__( 'Slide Up', 'gridd' ),
		],
		'priority'  => 10,
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'header_search_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_header_search',
		'default'   => '#ffffff',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-header_search',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'alpha' => true,
		],
		'priority'  => 20,
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'header_search_color',
		'label'     => esc_html__( 'Text Color', 'gridd' ),
		'section'   => 'grid_part_details_header_search',
		'default'   => '#000000',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-header_search',
				'property' => '--cl',
			],
		],
		'priority'  => 30,
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'header_search_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => esc_html__( 'Select the horizontal padding for this grid-part. Vertically there is no padding because the searchform occupies the whole height of this area.', 'gridd' ),
			]
		),
		'section'     => 'grid_part_details_header_search',
		'default'     => 1,
		'transport'   => 'auto',
		'output'      => [
			[
				'element'       => '.gridd-tp-header_search',
				'property'      => '--pd',
				'value_pattern' => '$rem',
			],
		],
		'choices'     => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'rem',
		],
		'priority'    => 40,
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'header_search_font_size',
		'label'       => esc_html__( 'Font Size', 'gridd' ),
		'description' => esc_html__( 'The value selected here is relative to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'     => 'grid_part_details_header_search',
		'default'     => 1,
		'output'      => [
			[
				'element'       => '.gridd-tp-header_search',
				'property'      => '--fs',
				'value_pattern' => '$rem',
			],
		],
		'choices'     => [
			'min'    => .5,
			'max'    => 4,
			'step'   => .01,
			'suffix' => 'rem',
		],
		'transport'   => 'auto',
		'priority'    => 50,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

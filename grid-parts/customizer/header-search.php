<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Customizer;

Customizer::add_outer_section(
	'gridd_grid_part_details_header_search',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Search', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_header_search',
			[
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/header/search/',
			]
		),
	]
);

Customizer::add_field(
	[
		'type'      => 'dimensions',
		'settings'  => 'gridd_grid_part_details_header_search_padding',
		'label'     => esc_html__( 'Padding', 'gridd' ),
		'tooltip'   => esc_html__( 'Select the left and right padding for this grid-part. Vertically there is no padding because the searchform occupies the whole height of this area.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => [
			'left'  => '1em',
			'right' => '1em',
		],
		'transport' => 'postMessage',
		'css_vars'  => [
			[ '--gridd-header-search-padding-left', '$', 'left' ],
			[ '--gridd-header-search-padding-right', '$', 'right' ],
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_part_details_header_search_font_size',
		'label'     => esc_html__( 'Font Size', 'gridd' ),
		'tooltip'   => esc_html__( 'The value selected here is relevant to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-header-search-font-size', '$em' ],
		'choices'   => [
			'min'    => .5,
			'max'    => 4,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_bg_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-search-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_search_color',
		'label'     => esc_html__( 'Text Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => '#000000',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-search-color',
	]
);

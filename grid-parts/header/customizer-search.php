<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;

gridd_add_customizer_section(
	'gridd_grid_part_details_header_search',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_attr__( '%s Options', 'gridd' ), esc_html__( 'Header Search', 'gridd' ) ),
		'section'     => 'gridd_grid',
		'description' => '<div class="gridd-section-description">%1$s%2$s</div><div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/header/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div></div>',
		'priority'    => 20,
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'dimensions',
		'settings'  => 'gridd_grid_part_details_header_search_padding',
		'label'     => esc_attr__( 'Padding', 'gridd' ),
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

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_part_details_header_search_font_size',
		'label'     => esc_attr__( 'Font Size', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-header-search-font-size', '$em' ],
		'choices'   => [
			'min'    => .7,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_bg_color',
		'label'     => esc_attr__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-search-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_search_color',
		'label'     => esc_attr__( 'Text Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_search',
		'default'   => '#000000',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-search-color',
	]
);

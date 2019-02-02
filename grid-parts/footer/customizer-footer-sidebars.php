<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Footer;
use Gridd\Customizer;

// Add sections & settings for widget areas.
$sidebars_nr = Footer::get_number_of_sidebars();
for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
	gridd_add_footer_widget_area_options( $i );
}

/**
 * Adds options for a footer widget-area.
 *
 * @since 1.0
 * @param int $id - The sidebar number.
 * @return void
 */
function gridd_add_footer_widget_area_options( $id ) {

	// Add section.
	gridd_add_customizer_section(
		"gridd_grid_part_details_footer_sidebar_$id",
		[
			'title'       => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The number of the footer widget area. */
				sprintf( esc_html__( 'Footer Sidebar %d', 'gridd' ), absint( $id ) )
			),
			'description' => Customizer::section_description(
				[
					'plus' => [
						esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
						esc_html__( 'Selecting from an array of WCAG-compliant colors for links', 'gridd' ),
					],
					'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/footer/',
				]
			),
			'section'     => 'gridd_grid_part_details_footer',
		]
	);

	// Background Color.
	gridd_add_customizer_field(
		[
			'type'      => 'color',
			'settings'  => "gridd_grid_footer_sidebar_{$id}_bg_color",
			'label'     => esc_html__( 'Background Color', 'gridd' ),
			'section'   => "gridd_grid_part_details_footer_sidebar_$id",
			'default'   => '#ffffff',
			'transport' => 'postMessage',
			'css_vars'  => "--gridd-footer-sidebar-$id-bg",
			'choices'   => [
				'alpha' => true,
			],
		]
	);

	// Text Color.
	gridd_add_customizer_field(
		[
			'type'      => 'kirki-wcag-tc',
			'settings'  => "gridd_grid_footer_sidebar_{$id}_color",
			'label'     => esc_html__( 'Text Color', 'gridd' ),
			'section'   => "gridd_grid_part_details_footer_sidebar_$id",
			'default'   => '#000000',
			'transport' => 'postMessage',
			'css_vars'  => "--gridd-footer-sidebar-$id-color",
			'choices'   => [
				'setting' => "gridd_grid_footer_sidebar_{$id}_bg_color",
			],
		]
	);

	// Links Color.
	gridd_add_customizer_field(
		[
			'type'      => 'gridd-wcag-lc',
			'settings'  => "gridd_grid_footer_sidebar_{$id}_links_color",
			'label'     => esc_html__( 'Links Color', 'gridd' ),
			'section'   => "gridd_grid_part_details_footer_sidebar_$id",
			'default'   => '#0f5e97',
			'transport' => 'postMessage',
			'css_vars'  => "--gridd-footer-sidebar-$id-links-color",
			'priority'  => 20,
			'choices'   => [
				'backgroundColor' => "gridd_grid_footer_sidebar_{$id}_bg_color",
				'textColor'       => "gridd_grid_footer_sidebar_{$id}_color",
			],
		]
	);
}

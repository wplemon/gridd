<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Footer;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

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

	$sanitization = new Sanitize();

	// Add section.
	Customizer::add_outer_section(
		"grid_part_details_footer_sidebar_$id",
		[
			'title' => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The number of the footer widget area. */
				sprintf( esc_html__( 'Footer Sidebar %d', 'gridd' ), absint( $id ) )
			),
		]
	);

	// Background Color.
	new \Kirki\Field\ReactColor(
		[
			'settings'  => "gridd_grid_footer_sidebar_{$id}_bg_color",
			'label'     => esc_html__( 'Background Color', 'gridd' ),
			'section'   => "grid_part_details_footer_sidebar_$id",
			'default'   => '#ffffff',
			'transport' => 'auto',
			'output'    => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id",
					'property' => '--bg',
				],
			],
			'choices'   => [
				'formComponent' => 'ChromePicker',
			],
		]
	);

	// Text Color.
	new \WPLemon\Field\WCAGTextColor(
		[
			'settings'          => "gridd_grid_footer_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "grid_part_details_footer_sidebar_$id",
			'default'           => '#000000',
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id",
					'property' => '--cl',
				],
			],
			'choices'           => [
				'backgroundColor' => "gridd_grid_footer_sidebar_{$id}_bg_color",
				'appearance'      => 'hidden',
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	// Links Color.
	new \WPLemon\Field\WCAGLinkColor(
		[
			'settings'          => "gridd_grid_footer_sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "grid_part_details_footer_sidebar_$id",
			'default'           => '#0f5e97',
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id",
					'property' => '--lc',
				],
			],
			'priority'          => 20,
			'choices'           => [
				'backgroundColor' => "gridd_grid_footer_sidebar_{$id}_bg_color",
				'textColor'       => "gridd_grid_footer_sidebar_{$id}_color",
				'linksUnderlined' => true,
				'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

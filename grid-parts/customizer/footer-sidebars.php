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

	/**
	 * Add Customizer sections.
	 *
	 * @since 1.2
	 */
	add_action(
		'customize_register',
		/**
		 * Register sections.
		 *
		 * @since 1.2
		 * @param WP_Customize The WordPress Customizer main object.
		 * @return void
		 */
		function( $wp_customize ) use ( $id ) {
			$wp_customize->add_section(
				new \Kirki\Module\Custom_Sections\Section_Outer(
					$wp_customize,
					"gridd_grid_part_details_footer_sidebar_$id",
					[
						'title'       => sprintf(
							/* translators: The grid-part label. */
							esc_html__( '%s Options', 'gridd' ),
							/* translators: The number of the footer widget area. */
							sprintf( esc_html__( 'Footer Sidebar %d', 'gridd' ), absint( $id ) )
						),
						'panel'       => 'gridd_hidden_panel',
						'description' => Customizer::section_description(
							"gridd_grid_part_details_footer_sidebar_$id",
							[
								'plus' => [
									esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
									esc_html__( 'Selecting from an array of WCAG-compliant colors for links', 'gridd' ),
								],
								'docs' => 'https://wplemon.github.io/gridd/grid-parts/footer.html',
							]
						),
					]
				)
			);
		}
	);

	// Background Color.
	Customizer::add_field(
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
	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'settings'          => "gridd_grid_footer_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "gridd_grid_part_details_footer_sidebar_$id",
			'default'           => '#000000',
			'transport'         => 'postMessage',
			'css_vars'          => "--gridd-footer-sidebar-$id-color",
			'choices'           => [
				'setting' => "gridd_grid_footer_sidebar_{$id}_bg_color",
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	// Links Color.
	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-lc',
			'settings'          => "gridd_grid_footer_sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "gridd_grid_part_details_footer_sidebar_$id",
			'default'           => '#0f5e97',
			'transport'         => 'postMessage',
			'css_vars'          => "--gridd-footer-sidebar-$id-links-color",
			'priority'          => 20,
			'choices'           => [
				'backgroundColor' => "gridd_grid_footer_sidebar_{$id}_bg_color",
				'textColor'       => "gridd_grid_footer_sidebar_{$id}_color",
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

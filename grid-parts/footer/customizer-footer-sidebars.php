<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Footer;

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
				esc_attr__( '%s Options', 'gridd' ),
				/* translators: The number of the footer widget area. */
				sprintf( esc_html__( 'Footer Sidebar %d', 'gridd' ), absint( $id ) )
			),
			'description' => sprintf(
				'<div class="gridd-section-description">%1$s%2$s</div>',
				( ! Gridd::is_pro() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for automatic WCAG-compliant colors suggestion on this section.', 'gridd' ) . '</div>' : '',
				'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/footer/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
			),
			'section'     => 'gridd_grid_part_details_footer',
		]
	);

	// Background Color.
	gridd_add_customizer_field(
		[
			'type'        => 'color',
			'settings'    => "gridd_grid_footer_sidebar_{$id}_bg_color",
			'label'       => esc_attr__( 'Background Color', 'gridd' ),
			'description' => esc_html__( 'Choose a background color for this widget-area.', 'gridd' ),
			'section'     => "gridd_grid_part_details_footer_sidebar_$id",
			'default'     => '#ffffff',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-footer-sidebar-$id-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	// Text Color.
	gridd_add_customizer_field(
		[
			'type'        => 'kirki-wcag-tc',
			'settings'    => "gridd_grid_footer_sidebar_{$id}_color",
			'label'       => esc_attr__( 'Text Color', 'gridd' ),
			'description' => esc_html__( 'The main text color used for the footer text.', 'gridd' ) . '<br>' . gridd()->customizer->get_text( 'a11y-textcolor-description' ),
			'tooltip'     => gridd()->customizer->get_text( 'a11y-textcolor-tooltip' ),
			'section'     => "gridd_grid_part_details_footer_sidebar_$id",
			'default'     => '#000000',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-footer-sidebar-$id-color",
			'choices'     => [
				'setting' => "gridd_grid_footer_sidebar_{$id}_bg_color",
			],
		]
	);

	// Links Color.
	gridd_add_customizer_field(
		[
			'type'        => 'kirki-wcag-lc',
			'settings'    => "gridd_grid_footer_sidebar_{$id}_links_color",
			'label'       => esc_attr__( 'Links Color', 'gridd' ),
			'description' => esc_html__( 'The color for links in this widget-area.', 'gridd' ),
			'section'     => "gridd_grid_part_details_footer_sidebar_$id",
			'default'     => '#0f5e97',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-footer-sidebar-$id-links-color",
			'priority'    => 20,
			'choices'     => [
				'backgroundColor' => "gridd_grid_footer_sidebar_{$id}_bg_color",
				'textColor'       => "gridd_grid_footer_sidebar_{$id}_color",
			],
		]
	);
}

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
	new \Kirki\Section(
		"footer_sidebar_$id",
		[
			/* translators: The number of the footer widget area. */
			'title'           => sprintf( esc_html__( 'Footer Sidebar %d', 'gridd' ), absint( $id ) ),
			'active_callback' => '__return_false',
		]
	);

	new \Kirki\Field\Checkbox_Switch(
		[
			'settings'  => "footer_sidebar_{$id}_custom_options",
			'section'   => "footer_sidebar_$id",
			'default'   => false,
			'transport' => 'refresh',
			'priority'  => -999,
			'choices'   => [
				'off' => esc_html__( 'Inherit Options', 'gridd' ),
				'on'  => esc_html__( 'Override Options', 'gridd' ),
			],
		]
	);

	// Background Color.
	new \Kirki\Field\ReactColor(
		[
			'settings'        => "footer_sidebar_{$id}_bg_color",
			'label'           => esc_html__( 'Background Color', 'gridd' ),
			'section'         => "footer_sidebar_$id",
			'default'         => '#ffffff',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id.custom-options",
					'property' => '--bg',
				],
			],
			'choices'         => [
				'formComponent' => 'TwitterPicker',
				'colors'        => \Gridd\Theme::get_colorpicker_palette(),
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "footer_sidebar_{$id}_custom_options", false );
			},
		]
	);

	// Text Color.
	new \WPLemon\Field\WCAGTextColor(
		[
			'settings'          => "footer_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "footer_sidebar_$id",
			'default'           => '#000000',
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id.custom-options",
					'property' => '--cl',
				],
			],
			'choices'           => [
				'backgroundColor' => "footer_sidebar_{$id}_bg_color",
				'appearance'      => 'hidden',
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => function() use ( $id ) {
				return get_theme_mod( "footer_sidebar_{$id}_custom_options", false );
			},
		]
	);

	// Links Color.
	new \WPLemon\Field\WCAGLinkColor(
		[
			'settings'          => "footer_sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "footer_sidebar_$id",
			'default'           => '#0f5e97',
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-footer_sidebar_$id.custom-options",
					'property' => '--lc',
				],
			],
			'priority'          => 20,
			'choices'           => [
				'backgroundColor' => "footer_sidebar_{$id}_bg_color",
				'textColor'       => "footer_sidebar_{$id}_color",
				'linksUnderlined' => true,
				'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => function() use ( $id ) {
				return get_theme_mod( "footer_sidebar_{$id}_custom_options", false );
			},
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

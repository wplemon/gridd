<?php
/**
 * Gridd Customizer
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

use Gridd\Customizer;
use Gridd\Customizer\Template;
use Gridd\Customizer\Control\Grid;
use Gridd\Customizer\Control\Gridd_Kirki_WCAG_Link_Color;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gridd_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'custom_logo' )->transport     = 'refresh';
}
add_action( 'customize_register', 'gridd_customize_register' );

add_filter(
	'kirki_control_types',
	function( $controls ) {
		$controls['gridd_grid'] = 'Gridd\Customizer\Control\Grid';
		if ( ! class_exists( 'Gridd\Customizer\Control\Gridd_Kirki_WCAG_Link_Color' ) ) {
			require_once get_template_directory() . '/inc/customizer/control/class-gridd-kirki-wcag-link-color.php';
		}
		$controls['gridd-wcag-lc'] = 'Gridd\Customizer\Control\Gridd_Kirki_WCAG_Link_Color';
		return $controls;
	}
);

/**
 * Registers the control type and make it eligible for
 * JS templating in the Customizer.
 *
 * @since 1.0
 * @param object $wp_customize The Customizer object.
 * @return void
 */
function gridd_kirki_wcag_link_color_register_control_type( $wp_customize ) {
	if ( ! class_exists( 'Gridd\Customizer\Control\Gridd_Kirki_WCAG_Link_Color' ) ) {
		require_once get_template_directory() . '/inc/customizer/control/class-gridd-kirki-wcag-link-color.php';
	}
	$wp_customize->register_control_type( 'Gridd\Customizer\Control\Gridd_Kirki_WCAG_Link_Color' );
}
add_action( 'customize_register', 'gridd_kirki_wcag_link_color_register_control_type' );

/**
 * Proxy function for Kirki.
 *
 * @since 1.0
 * @param array $args The field arguments.
 * @return void
 */
function gridd_add_customizer_field( $args ) {
	global $wp_customize;

	$args = apply_filters( 'gridd_field_args', $args );

	if ( 'gridd-wcag-tc' === $args['type'] ) {
		// No need to init a colorpicker if the setting is automated.
		$args['type']    = 'color';
		$auto_text_color = apply_filters( 'gridd_auto_text_color', Customizer::$auto_text_color );
		if ( in_array( $args['settings'], array_values( $auto_text_color ), true ) ) {
			$args['type']            = 'hidden';
			$args['active_callback'] = '__return_false';
		}
	}

	Kirki::add_field( 'gridd', $args );
	if ( 'gridd_grid' === $args['type'] ) {
		Customizer::$grid_controls[ $args['settings'] ] = $args;
	}
}

/**
 * Proxy function for Kirki.
 *
 * @since 1.0
 * @param string $id   The section ID.
 * @param array  $args The field arguments.
 * @return void
 */
function gridd_add_customizer_section( $id, $args ) {
	// WIP: Disable icons.
	if ( isset( $args['icon'] ) ) {
		unset( $args['icon'] );
	}

	Kirki::add_section( $id, apply_filters( 'gridd_section_args', $args ) );
}

/**
 * Proxy function for Kirki.
 * Adds an outer section.
 *
 * @since 1.0.3
 * @param string $id   The section ID.
 * @param array  $args The field arguments.
 * @return void
 */
function gridd_add_customizer_outer_section( $id, $args ) {
	$args['panel'] = 'gridd_hidden_panel';
	$args['type']  = 'outer';
	unset( $args['section'] );
	gridd_add_customizer_section( $id, $args );
}

/**
 * Proxy function for Kirki.
 *
 * @since 1.0
 * @param string $id   The section ID.
 * @param array  $args The field arguments.
 * @return void
 */
function gridd_add_customizer_panel( $id, $args ) {
	// WIP: Disable icons.
	if ( isset( $args['icon'] ) ) {
		unset( $args['icon'] );
	}
	Kirki::add_panel( $id, $args );
}

gridd_add_customizer_panel(
	'gridd_options',
	[
		'title'    => esc_html__( 'Theme Options', 'gridd' ),
		'priority' => 1,
	]
);

/**
 * This is a dummy panel used for outer sections.
 */
gridd_add_customizer_panel(
	'gridd_hidden_panel',
	[
		'title'    => '',
		'priority' => 999,
	]
);

/**
 * Add "Get Plus" section.
 *
 * @since 1.0
 */
if ( ! Gridd::is_plus_active() ) {
	gridd_add_customizer_section(
		'gridd_get_plus',
		[
			'title'       => esc_html__( 'Gridd Plus', 'gridd' ),
			'button_text' => esc_html__( 'Go Premium', 'gridd' ),
			'button_url'  => 'https://wplemon.com/gridd',
			'priority'    => -999,
			'type'        => 'link',
		]
	);
}
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function gridd_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function gridd_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Changes the stylesheet in which Kirki adds its styles.
 *
 * @since 1.0
 * @return string
 */
function gridd_kirki_stylesheet() {
	return 'gridd-style';
}
add_filter( 'kirki_gridd_stylesheet', 'gridd_kirki_stylesheet' );

/**
 * Adds some custom styles to the customizer.
 *
 * @since 1.0
 */
function gridd_customizer_custom_styles() {
	echo '<style>';
	echo '#sub-accordion-section-gridd_to_section .customize-control{margin-bottom:1em;padding-bottom:1em;border-bottom:1px solid rgba(0,0,0,.07);}';
	echo '#customize-controls .control-section.open .control-section-kirki-nested .accordion-section-title{background:#dedede!important;}';
	echo '</style>';
}
add_action( 'customize_controls_print_styles', 'gridd_customizer_custom_styles', 999 );

/**
 * Add the config.
 */
Kirki::add_config(
	'gridd',
	[
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	]
);

require_once get_template_directory() . '/inc/customizer/section/grid.php';
require_once get_template_directory() . '/inc/customizer/section/typography.php';
require_once get_template_directory() . '/inc/customizer/section/edd.php';
require_once get_template_directory() . '/inc/customizer/section/features.php';
if ( class_exists( 'WooCommerce' ) ) {
	require_once get_template_directory() . '/inc/customizer/section/woocommerce.php';
}

/**
 * Get the default value for the grid.
 *
 * @since 1.0
 * @return array
 */
function gridd_get_grid_default_value() {
	return [
		'rows'         => 4,
		'columns'      => 1,
		'areas'        => [
			'header'  => [
				'cells' => [ [ 1, 1 ] ],
			],
			'content' => [
				'cells' => [ [ 2, 1 ] ],
			],
			'footer'  => [
				'cells' => [ [ 3, 1 ] ],
			],
		],
		'gridTemplate' => [
			'rows'    => [ 'auto', 'auto', 'auto' ],
			'columns' => [ 'auto' ],
		],
	];
}

add_action(
	'customize_register',
	function( $wp_customize ) {
		$wp_customize->add_section(
			'gridd_template',
			[
				'title'    => esc_html__( 'Template', 'gridd' ),
				'priority' => 2,
			]
		);

		// Add Template Setting.
		$wp_customize->add_setting(
			'gridd_templates',
			[
				'default'           => [],
				'transport'         => 'refresh',
				'sanitize_callback' => '__return_true',
			]
		);
	}
);

/**
 * Move the background-color control to the modified background-image section.
 *
 * @since 1.0
 * @param WP_Customize The WordPress Customizer main object.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {

		// Move the background-color control.
		$wp_customize->get_control( 'background_color' )->section     = 'gridd_grid';
		$wp_customize->get_control( 'background_color' )->priority    = 90;
		$wp_customize->get_control( 'background_color' )->description = esc_html__( 'Background is visible under transparent grid-parts, or if the grid is not set to 100% width.', 'gridd' );

		// Move the background-image control.
		$wp_customize->get_control( 'background_image' )->section       = 'gridd_grid';
		$wp_customize->get_control( 'background_image' )->priority      = 90;
		$wp_customize->get_control( 'background_image' )->description   = esc_html__( 'Background is visible under transparent grid-parts, or if the grid is not set to 100% width.', 'gridd' );
		$wp_customize->get_control( 'background_preset' )->section      = 'gridd_grid';
		$wp_customize->get_control( 'background_preset' )->priority     = 90;
		$wp_customize->get_control( 'background_position' )->section    = 'gridd_grid';
		$wp_customize->get_control( 'background_position' )->priority   = 90;
		$wp_customize->get_control( 'background_size' )->section        = 'gridd_grid';
		$wp_customize->get_control( 'background_size' )->priority       = 90;
		$wp_customize->get_control( 'background_repeat' )->section      = 'gridd_grid';
		$wp_customize->get_control( 'background_repeat' )->priority     = 90;
		$wp_customize->get_control( 'background_attachment' )->section  = 'gridd_grid';
		$wp_customize->get_control( 'background_attachment' )->priority = 90;

		// Move the header-image control.
		$wp_customize->get_control( 'header_image' )->section  = 'gridd_grid_part_details_header';
		$wp_customize->get_control( 'header_image' )->priority = 80;
	}
);

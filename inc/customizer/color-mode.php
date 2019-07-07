<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

$content_bg = get_theme_mod( 'gridd_grid_content_background_color', '#ffffff' );
$dark_is_default = ( \Gridd\Color::get_contrast( $content_bg, '#ffffff' ) > \Gridd\Color::get_contrast( $content_bg, '#000000' ) );
var_dump( $dark_is_default );
Customizer::add_section(
	'gridd_color_scheme',
	[
		'title' => esc_html__( 'Color Scheme', 'gridd' ),
		'type'  => 'expanded',
		'priority' => 999,
	]
);

Customizer::add_field(
	[
		'settings' => 'default_to_dark',
		'default'  => $dark_is_default,
		'type'     => 'switch',
		'section'  => 'gridd_color_scheme',
		'label'    => esc_html__( 'Default to dark mode', 'gridd' ),
	]
);

Customizer::add_field(
	[
		'settings' => 'edit_mode',
		'default'  => '#ffffff' === strtolower( \Gridd\Color::get_readable_from_background( get_theme_mod( 'gridd_grid_content_background_color', '#ffffff' ) ) ),
		'type'     => 'switch',
		'section'  => 'gridd_color_scheme',
		'label'    => esc_html__( 'Default to dark mode', 'gridd' ),
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

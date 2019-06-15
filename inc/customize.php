<?php
/**
 * Gridd Customizer
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

/**
 * Add the "Theme Options" panel.
 *
 * @since 1.0
 */
Customizer::add_panel(
	'gridd_options',
	[
		'title'    => esc_html__( 'Theme Options', 'gridd' ),
		'priority' => 1,
	]
);

/**
 * Add a hidden panel used for outer sections.
 *
 * @since 1.0.3
 */
Customizer::add_panel(
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
if ( ! Theme::is_plus_active() ) {
	Customizer::add_section(
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
 * Add the config.
 */
\Kirki\Core\Kirki::add_config(
	'gridd',
	[
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	]
);

// Add customizer sections & settings.
require_once get_template_directory() . '/inc/customizer/grid.php';
require_once get_template_directory() . '/inc/customizer/typography.php';
require_once get_template_directory() . '/inc/customizer/edd.php';
require_once get_template_directory() . '/inc/customizer/features.php';
require_once get_template_directory() . '/inc/customizer/static-front-page.php';
require_once get_template_directory() . '/inc/customizer/woocommerce.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

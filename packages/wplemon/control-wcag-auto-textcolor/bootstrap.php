<?php
/**
 * Bootstrap the control.
 *
 * Addon control for the Kirki Toolkit for WordPress.
 * Adds a new colorpicker control to the WordPress Customizer,
 * allowing developers to build colorpickers that automatically suggest accessible link colors
 * depending on the value of a background color and the surrounding-text.
 *
 * @package    wcag-linkcolor
 * @category   Addon
 * @author     Ari Stathopoulos
 * @copyright  Copyright (c) 2019, Ari Stathopoulos
 * @license    https://opensource.org/licenses/MIT
 * @since      2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
    'kirki_control_types',

    /**
	 * Registers the control with Kirki.
	 *
	 * @since 1.0
	 * @param array $controls An array of controls registered with the Kirki Framework.
	 * @return array
	 */
    function( $controls ) {
        require_once __DIR__ . '/src/Control/WCAGTextColor.php';
		$controls['kirki-wcag-tc'] = '\WPLemon\Control\WCAGTextColor';
		return $controls;
    }
);

add_action(
    'customize_register',

    /**
	 * Registers the control type and make it eligible for
	 * JS templating in the Customizer.
	 *
	 * @since 1.0
	 * @param WP_Customize $wp_customize The Customizer object.
	 * @return void
	 */
	function( $wp_customize ) {
        require_once __DIR__ . '/src/Control/WCAGTextColor.php';
        $wp_customize->register_control_type( '\WPLemon\Control\WCAGTextColor' );

        // Add class aliases for backwards compatibility.
        class_alias( '\WPLemon\Control\WCAGTextColor', 'Kirki_WCAG_Text_Color' );
    },
    0
);

/**
 * Autoload Field for the Kirki v4.0 API.
 *
 * @since 2.0
 */
spl_autoload_register(
    /**
     * Autoload the class.
     *
     * @param string $class The class-name.
     */
	function( $class ) {
        if ( 'WPLemon\Field\WCAGTextColor' === $class || '\WPLemon\Field\WCAGTextColor' === $class ) {
            require_once __DIR__ . '/src/Field/WCAGTextColor.php';
        }
        if ( 'WPLemon\Control\WCAGTextColor' === $class || '\WPLemon\Control\WCAGTextColor' === $class ) {
            require_once __DIR__ . '/src/Control/WCAGTextColor.php';
        }
	}, false, true
);

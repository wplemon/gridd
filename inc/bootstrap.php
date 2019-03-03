<?php
/**
 * Bootstraps the Gridd theme.
 * We're using this file instead of functions.php to avoid fatal errors on PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gridd
 */

use Gridd\Theme;
use Gridd\Admin;
use Gridd\EDD;
use Gridd\AMP;
use Gridd\Widget_Output_Filters;

/**
 * If Kirki isn't loaded as a plugin, load the included version.
 */
if ( ! class_exists( 'Kirki' ) ) {
	require_once __DIR__ . '/kirki/kirki.php';
}

/**
 * Run an action to allow hooking addons.
 * This avoids any errors from addons if the active theme is not Gridd.
 *
 * @since 1.0.3
 */
do_action( 'gridd_setup' );

/**
 * The Gridd Autoloader.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
	function( $class ) {
		$prefix   = 'Gridd\\';
		$base_dir = __DIR__ . '/classes/';

		$len = strlen( $prefix );
		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}
		$relative_class = substr( $class, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Load the textdomain.
 *
 * @since 1.0
 */
function gridd_load_theme_textdomain() {
	load_theme_textdomain( 'gridd', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'gridd_load_theme_textdomain' );

/**
 * Instantiate the main theme object.
 *
 * @since 1.0
 */
Theme::get_instance();

/**
 * Add widget mods.
 *
 * @since 1.0
 */
Widget_Output_Filters::get_instance();

/**
 * Load admin tweaks.
 *
 * @since 1.0
 */
new Admin();

/**
 * Load EDD mods.
 *
 * @since 1.0
 */
new EDD();

/**
 * Customizer additions.
 *
 * @since 1.0
 */
require __DIR__ . '/customize.php';

/**
 * AMP Support.
 *
 * @since 1.0
 */
new AMP();

/**
 * Integrates WPBakery Builder in the theme.
 *
 * @since 1.0
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
	add_action( 'vc_before_init', 'vc_set_as_theme' );
	add_filter( 'vc_nav_front_logo', '__return_empty_string' );
}

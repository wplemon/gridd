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
use Gridd\Rest;
use Gridd\Rest_Routes;
use Gridd\Upgrade;
use Gridd\Editor;

require_once dirname( __DIR__ ) . '/packages/autoload.php';
require_once __DIR__ . '/kirki.php';

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
 * Run an action to allow hooking addons.
 * This avoids any errors from addons if the active theme is not Gridd.
 *
 * @since 1.0.3
 */
do_action( 'gridd_setup' );

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
 * REST-API implementation.
 *
 * @since 1.1
 */
new Rest();

/**
 * REST-API Routes.
 *
 * @since 1.1
 */
new Rest_Routes();

/**
 * Run theme upgrades.
 *
 * @since 1.1.18
 */
new Upgrade();

/**
 * Editor tweaks.
 *
 * @since 1.2.0
 */
new Editor();

/**
 * Integrates WPBakery Builder in the theme.
 *
 * @since 1.0
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
	add_action( 'vc_before_init', 'vc_set_as_theme' );
	add_filter( 'vc_nav_front_logo', '__return_empty_string' );
}

/**
 * Custom CSS should be at the end of everything else in order to override existing styles.
 * We need to unhook it from wp_head and hook it in wp_footer.
 */
add_action(
	'wp_head',
	function() {
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
		add_action( 'wp_footer', 'wp_custom_css_cb', PHP_INT_MAX );
	},
	10
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

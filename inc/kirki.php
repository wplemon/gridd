<?php
/**
 * Init Kirki.
 *
 * @package Gridd
 * @since   1.2.0
 */

if ( class_exists( 'Kirki' ) ) {
	return;
}
use Kirki\Compatibility\Init;
use Kirki\L10n;
use Kirki\Compatibility\Modules;
use Kirki\Compatibility\Framework;
use Kirki\Compatibility\Kirki;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-aricolor.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

new \Kirki\Compatibility\Aliases();
new \Kirki\Compatibility\Scripts();
new \Kirki\Compatibility\Deprecated();

if ( ! function_exists( 'Kirki' ) ) {
	/**
	 * Returns an instance of the Kirki object.
	 */
	function kirki() {
		$kirki = Framework::get_instance();
		return $kirki;
	}
}

// Start Kirki.
global $kirki;
$kirki = kirki();

// Instantiate the modules.
$kirki->modules = new Modules();

// Instantiate classes.
new Kirki();
new L10n( 'kirki', __DIR__ . '/languages' );

// Add an empty config for global fields.
Kirki::add_config( '' );

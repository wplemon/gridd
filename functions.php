<?php
/**
 * Gridd functions and definitions
 *
 * @package Gridd
 */

/**
 * Gracefully fail if the user is on an old PHP version
 * or if using an old version of WordPress.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.9.6', '<' ) || version_compare( PHP_VERSION, '5.6', '<' ) ) {
	require_once get_template_directory() . '/inc/bootstrap-compatibility.php';
	return;
}

// Bootstrap the theme.
require_once get_template_directory() . '/inc/bootstrap.php';

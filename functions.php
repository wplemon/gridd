<?php
/**
 * Gridd functions and definitions
 *
 * @package Gridd
 */

/**
 * The theme version.
 *
 * @since 1.0
 */
define( 'GRIDD_VERSION', '1.1.7' );

/**
 * Gracefully fail if the user is on an old PHP version
 * or if using an old version of WordPress.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) || version_compare( PHP_VERSION, '5.6', '<' ) ) {
	require_once get_template_directory() . '/inc/bootstrap-compatibility.php';
	return;
}

// Bootstrap the theme.
require_once get_template_directory() . '/inc/bootstrap.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

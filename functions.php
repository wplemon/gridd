<?php
/**
 * Gridd functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gridd
 */

/**
 * If the user is on an old PHP version, throw a error in the dashboard
 * so that they may change themes.
 * After that, early exit and don't continue bootstrapping the theme.
 */
if ( version_compare( PHP_VERSION, '5.6.0' ) < 0 ) {
	/**
	 * Admin notice for PHP version.
	 * NON dismissable because if the user doesn't take any action their site won't work.
	 *
	 * @since 1.0
	 */
	function gridd_admin_notice_php_version() {
		echo '<div class="notice notice-error"><p style="font-size:16px">';
		_e( '<strong>ERROR</strong>: The Gridd Theme requires a PHP version greater than 5.6. Please visit <a href="https://wordpress.org/support/update-php/" rel="nofollow">this link</a> for instructions on how to update your PHP version, or switch to a different theme.', 'gridd' );
		echo '</p></div>';
	}
	add_action( 'admin_notices', 'gridd_admin_notice_php_version' );
} else {
	/**
	 * All good, we're on a recent-ish PHP version. Proceed.
	 */
	require_once get_template_directory() . '/inc/bootstrap.php';
}

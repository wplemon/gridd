<?php
/**
 * Admin pages etc.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

/**
 * Handle pages in the dashboard.
 *
 * @since 1.0
 */
class Admin {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		// Add Kirki-installation nag.
		add_action( 'admin_notices', [ $this, 'kirki_notice' ] );

		// Add VC styles.
		add_action( 'admin_footer', [ $this, 'vc_styles' ] );
	}

	/**
	 * The kirki notice.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function kirki_notice() {
		include get_template_directory() . '/inc/admin/templates/html-notice-kirki.php';
	}

	/**
	 * Enqueue custom VC styles.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function vc_styles() {
		echo '<style>';
		include get_template_directory() . '/assets/css/plugins/admin-vc-edit.min.css';
		echo '</style>';
	}
}

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
 * @since 0.1
 */
class Admin {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		// Add VC styles.
		add_action( 'admin_footer', [ $this, 'vc_styles' ] );
	}

	/**
	 * Enqueue custom VC styles.
	 *
	 * @since 0.1
	 * @return void
	 */
	public function vc_styles() {
		echo '<style>';
		include get_template_directory() . '/assets/css/plugins/admin-vc-edit.min.css';
		echo '</style>';
	}
}

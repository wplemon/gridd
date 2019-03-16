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

		// Add VC styles.
		add_action( 'admin_footer', [ $this, 'vc_styles' ] );
	}

	/**
	 * Enqueue custom VC styles.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function vc_styles() {
		// If visual-composer (WPBakery builder) is not installed early exit.
		if ( ! class_exists( 'Vc_Manager' ) ) {
			return;
		}

		// This style can't be enqueued, it only works on visual-composer if added inline.
		// This is a simple stylesheet containing tweaks to make the layouts properly work while in the builder.
		echo '<style>';
		include get_template_directory() . '/assets/css/plugins/admin-vc-edit.min.css';
		echo '</style>';
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

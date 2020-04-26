<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Admin pages etc.
 *
 * @package Gridd
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
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

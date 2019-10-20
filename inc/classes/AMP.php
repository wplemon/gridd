<?php // phpcs:ignore WordPress.Files.FileName
/**
 * AMP Support & related functionality.
 *
 * @package Gridd
 */

namespace Gridd;

/**
 * Extra methods and actions for AMP.
 *
 * @since 1.0
 */
class AMP {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {

		if ( ! self::is_active() ) {
			return;
		}
		require_once get_template_directory() . '/inc/amp/bootstrap.php';
	}

	/**
	 * Check if AMP mode is active.
	 * We simply check if the is_amp_endpoint function exists here.
	 * If it exists, then the AMP plugin is installed & activated
	 * so since we force Native mode, AMP is always on.
	 *
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function is_active() {

		// We don't need to check if this is an AMP endpoint because we're working in native mode.
		// If the function exists, it IS an AMP endpoint.
		return ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

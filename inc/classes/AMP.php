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
		$this->add_notice();
		add_filter( 'wptrt_admin_notices_allowed_html', [ $this, 'wptrt_admin_notices_allowed_html' ] );
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
		return ( function_exists( 'is_amp_endpoint' ) );
	}

	/**
	 * Adds admin notice urging users to install the companion plugin for AMP compatibility.
	 *
	 * @access protected
	 * @since 1.2.0
	 * @return void
	 */
	protected function add_notice() {
		//Early exit if plugin is already enabled.
		if ( defined( 'GRIDD_AMP_VERSION' ) ) {
			return;
		}
		$amp_notice = new \WPTRT\AdminNotices\Notices();
		$amp_notice->add(
			// Notice ID.
			'gridd_amp_install',
			// Title.
			__( 'Additional plugin recommended for AMP + Gridd', 'gridd' ),
			// Content.
			sprintf(
				/* translators: Plugin URL. */
				__( 'We detected you are using the Gridd theme on an AMP-enabled site. We recommend you install the <a href="%s" target="_blank">Gridd-AMP companion plugin</a> to ensure maximum compatibility', 'textdomain' ),
				'https://github.com/wplemon/gridd-amp'
			),
		);
		$amp_notice->boot();
	}

	/**
	 * Allows the use of the "target" argument for links.
	 *
	 * @access public
	 * @since 1.2.0
	 * @param array $allowed_html
	 * @return array
	 */
	public function wptrt_admin_notices_allowed_html( $allowed_html ) {
		$allowed_html['a']['target'] = [];
		return $allowed_html;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

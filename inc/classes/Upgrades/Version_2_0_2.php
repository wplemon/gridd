<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Run upgrades for version 2.0.2
 *
 * @since 2.0.2
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 2.0.2
 */
class Version_2_0_2 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 2.0.2
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 2.0.2
	 * @return void
	 */
	private function run_update() {
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 2.0.2
	 * @return void
	 */
	private function content_padding() {
		set_theme_mod( 'breadcrumbs_custom_options', true );

		$number = Navigation::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "nav_{$i}_custom_options", true );
		}
	}
}

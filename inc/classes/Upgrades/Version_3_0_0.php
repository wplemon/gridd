<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Run upgrades for version 3.0.0
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 3.0.0
 */
class Version_3_0_0 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function run_update() {
		$this->custom_options();
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function custom_options() {
		set_theme_mod( 'breadcrumbs_custom_options', true );

		$number = \Gridd\Grid_Part\Navigation::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "nav_{$i}_custom_options", true );
		}
	}
}

<?php // phpcs:ignoreFile WordPress.Files.FileName
/**
 * Run upgrades for version 1.1.18
 *
 * @since 1.1.18
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 1.1.18
 */
class Version_1_1_18 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1.18
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return void
	 */
	private function run_update() {
		$this->set_custom_typography_switches();
	}

	/**
	 * Sets the custom-typography switches.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return void
	 */
	private function set_custom_typography_switches() {
		foreach ( [ 'gridd_body_typography', 'gridd_headers_typography' ] as $typo_option ) {
			$value            = get_theme_mod( $typo_option );
			$is_standard_font = false;
			$font_family      = '';

			if ( $value && is_array( $value ) && isset( $value['font-family'] ) ) {

				// Check if the saved value is a default one.
				$is_standard_font = (
					in_array( $value['font-family'], [ '', 'initial', 'inherit' ], true ) ||
					strpos( $value['font-family'], ',serif' ) ||
					strpos( $value['font-family'], ', serif' ) ||
					strpos( $value['font-family'], ',sans-serif' ) ||
					strpos( $value['font-family'], ', sans-serif' )
				);
			} else {

				// If we got here then value is not set so it's the default.
				$is_standard_font = true;
			}

			$is_google_font = ! $is_standard_font;

			if ( 'gridd_body_typography' === $typo_option ) {
				set_theme_mod( 'custom_body_typography', $is_google_font );
			}
			if ( 'gridd_headers_typography' === $typo_option ) {
				set_theme_mod( 'custom_headers_typography', $is_google_font );
			}
		}
	}
}

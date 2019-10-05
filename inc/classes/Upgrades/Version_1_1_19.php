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
class Version_1_1_19 {

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
		$this->modified_defaults();
		$this->content_padding();
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 1.1.19
	 * @return void
	 */
	private function modified_defaults() {
		$settings_old_defaults = [
			'gridd_grid_content_max_width' => '45em',
			'gridd_grid_header_max_width'  => '45em',
		];
		foreach ( $settings_old_defaults as $setting => $default ) {
			$value = get_theme_mod( $setting );

			if ( ! $value ) {
				set_theme_mod( ltrim( $setting, 'gridd_grid_' ), $default );
			}
		}
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 1.1.19
	 * @return void
	 */
	private function content_padding() {
		$content_padding = get_theme_mod(
			'gridd_grid_content_padding',
			[
				'top'    => '0',
				'bottom' => '0',
				'left'   => '20px',
				'right'  => '20px',
			]
		);
		$top    = $content_padding['top'] ? $content_padding['top'] : '0';
		$bottom = $content_padding['bottom'] ? $content_padding['bottom'] : '';
		$left   = $content_padding['left'] ? $content_padding['left'] : '20px';
		$right  = $content_padding['right'] ? $content_padding['right'] : '20px';

		// Calculate horizontal padding.
		$left       = ( 5 > intval( $left ) ) ? intval( $left ) : intval( $left ) / 20;
		$right      = ( 5 > intval( $right ) ) ? intval( $right ) : intval( $right ) / 20;
		$horizontal = ( $left + $right ) / 2;
		$horizontal = max( 0, min( $horizontal, 10 ) );
		set_theme_mod( 'content_padding_horizontal', $horizontal );

		// Save top & bottom padding values.
		$top    = ( 5 > intval( $top ) ) ? intval( $top ) : intval( $top ) / 20;
		$top    = max( 0, min( $top, 10 ) );
		$bottom = ( 5 > intval( $bottom ) ) ? intval( $bottom ) : intval( $bottom ) / 20;
		$bottom = max( 0, min( $bottom, 10 ) );
		set_theme_mod( 'content_padding_top', $top );
		set_theme_mod( 'content_padding_bottom', $bottom );
	}
}

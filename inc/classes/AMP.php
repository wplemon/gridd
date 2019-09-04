<?php
/**
 * AMP Support & related functionality.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
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

		// Additional css-vars.
		add_action( 'wp_head', [ $this, 'css_vars_calc' ] );

		// Add theme-support for AMP Native mode.
		add_action( 'after_setup_theme', [ $this, 'add_theme_support' ] );

		// Disable AMP in the customizer.
		if ( is_customize_preview() ) {
			add_filter( 'amp_is_enabled', '__return_false' );
		}
	}

	/**
	 * Adds additional css-vars in the <head> of the document.
	 * In non-AMP sites these get calculated via JS so this is just a fallback.
	 * It won't always be 100% correct (that's why we were using JS to calculate them)
	 * but it will be a pretty close approximation.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function css_vars_calc() {
		$content_max_width                  = get_theme_mod( 'gridd_grid_part_details_content', '45em' );
		$main_font_size                     = get_theme_mod( 'gridd_body_font_size', 18 ) . 'px';
		$fluid_typo_ratio                   = get_theme_mod( 'gridd_fluid_typography_ratio', 0.25 );
		$gridd_content_max_width_calculated = $content_max_width;

		// If we're using "em" for the content area'smax-width,
		// then we needto make some calculations for the $c-mw-c css-var.
		if ( false === strpos( $content_max_width, 'rem' ) && false !== strpos( $content_max_width, 'em' ) ) {

			// Check that there are numbers in our value and that we're not using calc.
			if ( preg_match( '#[0-9]#', $main_font_size ) ) {

				// This is an approximation. We'll be multiplying the ratio to get an average size.
				$ratio = 1 + 2 * $fluid_typo_ratio;

				// Get the numeric value for the font-size.
				$font_size_numeric = filter_var( $main_font_size, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

				// Get the numeric value for the content's max-width.
				$content_max_width_numeric = filter_var( $content_max_width, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

				// Fallback to pixels for the font-size.
				$font_size_unit = 'px';

				// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
				$units = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];
				foreach ( $units as $unit ) {
					if ( false !== strpos( $main_font_size, $unit ) ) {
						$font_size_unit = $unit;
						break;
					}
				}

				$gridd_content_max_width_calculated = ( $content_max_width_numeric * $ratio * $font_size_numeric ) . $font_size_unit;
			}
		}

		// We use esc_attr() for sanitization here since we want to sanitize a CSS value.
		// Though not strictly accurate, in this case it is secure and doesn't cause any issues.
		echo '<style>body{--c-mw-c:' . esc_attr( $gridd_content_max_width_calculated ) . ';</style>';
	}

	/**
	 * Adds theme-support for AMP - native mode.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function add_theme_support() {
		add_theme_support(
			'amp',
			[
				'paired' => false,
			]
		);
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
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Color utilities.
 * These are a copy of the JS functions on https://aristath.github.io/wcagColors.js/
 *
 * @package Gridd
 */

namespace Gridd;

use \ariColor;

/**
 * A collection of a11y utilities.
 *
 * @since 1.0
 */
class Color {

	/**
	 * Get readable color from background-color.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $color The background color.
	 * @return string       The text color (hex).
	 */
	public static function get_readable_from_background( $color ) {
		return self::get_contrast( $color, '#ffffff' ) > self::get_contrast( $color, '#000000' ) ? '#ffffff' : '#000000';
	}

	/**
	 * Get relative luminance from an RGB array.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $rgb An array [R,G,B].
	 * @return float
	 */
	public static function get_relative_luminance( $rgb ) {
		$rgb[0] /= 255;
		$rgb[1] /= 255;
		$rgb[2] /= 255;

		$red   = ( 0.03928 >= $rgb[0] ) ? $rgb[0] / 12.92 : pow( ( ( $rgb[0] + 0.055 ) / 1.055 ), 2.4 );
		$green = ( 0.03928 >= $rgb[1] ) ? $rgb[1] / 12.92 : pow( ( ( $rgb[1] + 0.055 ) / 1.055 ), 2.4 );
		$blue  = ( 0.03928 >= $rgb[2] ) ? $rgb[2] / 12.92 : pow( ( ( $rgb[2] + 0.055 ) / 1.055 ), 2.4 );

		return 0.2126 * $red + 0.7152 * $green + 0.0722 * $blue;
	}

	/**
	 * Get the contrast between 2 colors.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $colors The 2 colors as strings ['#fff','#000'].
	 * @return float
	 */
	public static function get_contrast( $colors ) {
		if ( ! class_exists( '\ariColor' ) ) {
			return;
		}
		$color1 = \ariColor::newColor( $colors[0] );
		$color2 = \ariColor::newColor( $colors[1] );
		$l1     = self::get_relative_luminance( [ $color1->red, $color1->green, $color1->blue ] );
		$l2     = self::get_relative_luminance( [ $color2->red, $color2->green, $color2->blue ] );
		return max( ( $l1 + 0.05 ) / ( $l2 + 0.05 ), ( $l2 + 0.05 ) / ( $l1 + 0.05 ) );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

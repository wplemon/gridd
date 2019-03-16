<?php
/**
 * Sanitization callbacks for the Customizer.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Customizer;

/**
 * Extra methods and actions for AMP.
 *
 * @since 1.0
 */
class Sanitize {

	/**
	 * Sanitization callback for the grid control.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $value The control value.
	 * @return array       The value - sanitized.
	 */
	public function grid( $value ) {

		// If string, json-decode first.
		if ( is_string( $value ) ) {
			$value = json_decode( $value, true );
		}

		// Areas.
		$areas   = [];
		$max_col = 1;
		$max_row = 1;
		if ( isset( $value['areas'] ) && is_array( $value['areas'] ) ) {
			foreach ( $value['areas'] as $area_key => $area_value ) {
				$area_key = sanitize_key( $area_key );
				if ( isset( $area_value['cells'] ) && is_array( $area_value['cells'] ) ) {
					$areas[ $area_key ] = [
						'cells' => [],
					];
					foreach ( $area_value['cells'] as $cell ) {

						// Sanitize row & column numbers.
						$row = absint( $cell[0] );
						$col = absint( $cell[1] );

						// Set the array.
						$areas[ $area_key ]['cells'][] = [ $row, $col ];

						// Calculate max-col & max-row.
						$max_row = max( $row, $max_row );
						$max_col = max( $col, $max_col );
					}
				}
			}
		}
		$value['areas'] = $areas;

		// Rows.
		$rows          = [];
		$value['rows'] = max( $max_row, absint( $value['rows'] ) );
		for ( $i = 0; $i <= $value['rows']; $i++ ) {
			$rows[ $i ] = 'auto';
			if ( isset( $value['gridTemplate']['rows'][ $i ] ) ) {
				$rows[ $i ] = esc_attr( $value['gridTemplate']['rows'][ $i ] );
			}
		}
		$value['gridTemplate']['rows'] = $rows;

		// Columns.
		$columns          = [];
		$value['columns'] = max( $max_col, absint( $value['columns'] ) );
		for ( $i = 0; $i <= $value['columns']; $i++ ) {
			$columns[ $i ] = 'auto';
			if ( isset( $value['gridTemplate']['columns'][ $i ] ) ) {
				$columns[ $i ] = esc_attr( $value['gridTemplate']['columns'][ $i ] );
			}
		}
		$value['gridTemplate']['columns'] = $columns;

		return $value;
	}

    /**
     * Sanitises a HEX value.
     * The way this works is by splitting the string in 6 substrings.
     * Each sub-string is individually sanitized, and the result is then returned.
     *
     * @access public
     * @param  string  The hex value of a color
     * @param  boolean Whether we want to include a hash (#) at the beginning or not
     * @return string  The sanitized hex color.
     */
    public function color_hex( $color = '#FFFFFF', $hash = true ) {

        // Remove any spaces and special characters before and after the string
        $color = trim( trim( $color ), '#' );

        // If the string is 6 characters long then use it in pairs.
        if ( 3 == strlen( $color ) ) {
            $color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
        }

        $substr  = array();
        for ( $i = 0; $i <= 5; $i++ ) {
            $default      = ( 0 == $i ) ? 'F' : ( $substr[$i-1] );
            $substr[ $i ] = substr( $color, $i, 1 );
            $substr[ $i ] = ( false === $substr[ $i ] || ! ctype_xdigit( $substr[ $i ] ) ) ? $default : $substr[ $i ];
        }
        $hex = implode( '', $substr );

        return ( ! $hash ) ? $hex : '#' . $hex;
    }
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

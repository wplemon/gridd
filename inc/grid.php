<?php
/**
 * Extra bits and pieces needed for the grid
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Customizer;
use Gridd\Grid_Part\Header;
use Gridd\Grid_Part\Footer;

/**
 * Template handler.
 *
 * @since 1.0
 */
class Grid {

	/**
	 * Gets the theme options for the grid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $theme_mod The theme-mod we're checking.
	 * @param array  $defaults  The defaults for the theme-mod.
	 * @return array
	 */
	public static function get_options( $theme_mod = 'gridd_grid', $defaults = [] ) {

		// Get default values.
		if ( ! $defaults || empty( $defaults ) ) {
			$defaults = [];
			switch ( $theme_mod ) {
				case 'gridd_grid':
					$defaults = gridd_get_grid_default_value();
					break;

				case 'gridd_header_grid':
					$defaults = Header::get_grid_defaults();
					break;

				case 'gridd_footer_grid':
					$defaults = Footer::get_grid_defaults();
					break;
			}
		}

		$defaults = apply_filters( 'gridd_get_options_defaults', $defaults, $theme_mod );

		$grid = get_theme_mod( $theme_mod, $defaults );
		if ( ! is_array( $grid ) ) {
			$grid_json = json_decode( $grid, true );
			if ( $grid_json ) {
				$grid = $grid_json;
			}
		}
		return wp_parse_args( $grid, $defaults );
	}

	/**
	 * Adds styles for the grid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array  $settings     The theme-mods for this grid. If empty it will use the default grid.
	 * @param string $selector     The CSS selector for the grid.
	 * @param bool   $prefix_parts Whether we should prefix the parts CSS with the wrapper or not.
	 * @return string
	 */
	public static function get_styles( $settings = [], $selector = '.gridd-site-wrapper', $prefix_parts = false ) {
		$css = '';

		if ( empty( $settings ) ) {
			$settings = self::get_options();
		}

		if ( isset( $settings['gridTemplate'] ) && isset( $settings['gridTemplate']['rows'] ) && isset( $settings['gridTemplate'] ) && isset( $settings['gridTemplate']['columns'] ) ) {

			// The Rows CSS.
			foreach ( $settings['gridTemplate']['rows'] as $key => $val ) {
				if ( ! $val ) {
					$settings['gridTemplate']['rows'][ $key ] = 'auto';
				}
			}
			$settings['gridTemplate']['rows'] = array_slice( $settings['gridTemplate']['rows'], 0, absint( $settings['rows'] ) );

			// The Columns CSS.
			foreach ( $settings['gridTemplate']['columns'] as $key => $val ) {
				if ( ! $val ) {
					$settings['gridTemplate']['columns'][ $key ] = 'auto';
				}
			}
			$settings['gridTemplate']['columns'] = array_slice( $settings['gridTemplate']['columns'], 0, absint( $settings['columns'] ) );

			$css .= htmlspecialchars_decode( $selector . '{' );
			$css .= 'display:grid;';
			$css .= htmlspecialchars_decode( 'grid-template-rows:' . implode( ' ', $settings['gridTemplate']['rows'] ) . ';' );
			$css .= htmlspecialchars_decode( '-ms-grid-rows:' . implode( ' ', $settings['gridTemplate']['rows'] ) . ';' );
			$css .= htmlspecialchars_decode( 'grid-template-columns:' . esc_attr( implode( ' ', $settings['gridTemplate']['columns'] ) ) . ';' );
			$css .= htmlspecialchars_decode( '-ms-grid-columns:' . esc_attr( implode( ' ', $settings['gridTemplate']['columns'] ) ) . ';' );
			$css .= htmlspecialchars_decode( '}' );
		}

		$prefix = $prefix_parts ? $selector . ' ' : '';

		// Template-parts CSS.
		if ( isset( $settings['areas'] ) ) {
			$grid_parts = array_keys( $settings['areas'] );
			foreach ( $grid_parts as $grid_part ) {
				$css .= $prefix . '.gridd-tp-' . $grid_part . '{';

				// Grid.
				$specs = [
					'row'    => [],
					'column' => [],
				];

				$row_cells    = [];
				$column_cells = [];

				if ( isset( $settings['areas'] ) && isset( $settings['areas'][ $grid_part ] ) && isset( $settings['areas'][ $grid_part ]['cells'] ) ) {
					foreach ( $settings['areas'][ $grid_part ]['cells'] as $cell ) {
						$row_cells[]    = $cell[0];
						$column_cells[] = $cell[1];
					}
					$row_cells    = array_unique( $row_cells );
					$column_cells = array_unique( $column_cells );

					$specs['row']['start']    = min( $row_cells );
					$specs['column']['start'] = min( $column_cells );
					$specs['row']['end']      = $specs['row']['start'] + count( $row_cells );
					$specs['column']['end']   = $specs['column']['start'] + count( $column_cells );
				}

				if ( isset( $specs['row'] ) && isset( $specs['row']['start'] ) && isset( $specs['row']['end'] ) ) {
					$css .= '-ms-grid-row:' . intval( $specs['row']['start'] ) . ';';
					$css .= '-ms-grid-row-span:' . ( intval( $specs['row']['end'] ) - intval( $specs['row']['start'] ) ) . ';';
					$css .= 'grid-row:' . intval( $specs['row']['start'] ) . ' / ' . intval( $specs['row']['end'] ) . ';';
				}
				if ( isset( $specs['column'] ) && isset( $specs['column']['start'] ) && isset( $specs['column']['end'] ) ) {
					$css .= '-ms-grid-column:' . intval( $specs['column']['start'] ) . ';';
					$css .= '-ms-grid-column-span:' . ( intval( $specs['column']['end'] ) - intval( $specs['column']['start'] ) ) . ';';
					$css .= 'grid-column:' . intval( $specs['column']['start'] ) . ' / ' . intval( $specs['column']['end'] ) . ';';
				}
				$css .= '}';
			}
		}
		return $css;
	}

	/**
	 * Responsive styles.
	 * Return CSS for a desktop grid, a mobile grid, and takes a custom breakpoint.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $args The arguments.
	 * @return void
	 */
	public static function the_styles_responsive( $args ) {
		echo self::get_styles_responsive( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * Responsive styles.
	 * Outputs CSS for a desktop grid, a mobile grid, and takes a custom breakpoint.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $args The arguments.
	 * @return string
	 */
	public static function get_styles_responsive( $args ) {
		$args = apply_filters( 'gridd_styles_responsive_args', $args );

		$args['prefix'] = ( isset( $args['prefix'] ) ) ? $args['prefix'] : false;

		$hidden_small_parts = [];
		$hidden_large_parts = [];

		$css = '';

		$args['small'] = ( empty( $args['small'] ) ) ? false : $args['small'];

		$css .= "{$args['selector']}{display:grid;}";

		if ( $args['small'] ) {

			// Add breakpoint for small screens.
			$css .= "@media only screen and (max-width:{$args['breakpoint']}){";

			// Add styles for mobile grid.
			$css .= self::get_styles( $args['small'], $args['selector'], $args['prefix'] );

			// Find parts in the large grid that don't exist in the small grid.
			foreach ( array_keys( $args['large']['areas'] ) as $key ) {
				if ( ! isset( $args['small']['areas'][ $key ] ) ) {
					$hidden_small_parts[] = $key;
				}
			}

			// Hide parts that don't exist in the small grid.
			if ( ! empty( $hidden_small_parts ) ) {
				foreach ( $hidden_small_parts as $hidden_small_part ) {
					$css .= ".gridd-tp.gridd-tp-$hidden_small_part{display:none;}";
				}
			}

			// Close breakpoint for small screens.
			$css .= '}';
		}

		// Add breakpoint for large screens.
		$css .= "@media only screen and (min-width:{$args['breakpoint']}){";

		// Add styles for the large-screens grid.
		$css .= self::get_styles( $args['large'], $args['selector'], $args['prefix'] );

		// Find parts in the small grid that don't exist in the large grid.
		if ( $args['small'] ) {
			foreach ( array_keys( $args['small']['areas'] ) as $key ) {
				if ( ! isset( $args['large']['areas'][ $key ] ) ) {
					$hidden_large_parts[] = $key;
				}
			}
		}

		// Hide parts that don't exist in the small grid.
		foreach ( $hidden_large_parts as $hidden_large_part ) {
			$css .= ".gridd-tp.gridd-tp-$hidden_large_part{display:none;}";
		}

		// Close breakpoint for large screens.
		$css .= '}';

		return $css;
	}
}

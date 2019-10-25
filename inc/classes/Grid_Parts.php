<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The Grid_Parts class.
 *
 * @package Gridd
 */

namespace Gridd;

use Gridd\Grid\Part;
use Gridd\Grid_Parts;

/**
 * Grid-Part.
 *
 * @since 1.0
 */
class Grid_Parts {

	/**
	 * The parts array.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $parts;

	/**
	 * A single instance of this object.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var Gridd\Grid_Parts
	 */
	private static $instance;

	/**
	 * Get an instance of this object.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return Gridd\Grid_Parts
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access private
	 */
	private function __construct() {

		// Include all parts files.
		$this->include_grid_part_files();

		// Set $this->parts.
		$this->set_parts();
	}

	/**
	 * Get an array of active template parts.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_active() {
		$grid_parts = [];

		// Get all grid-parts.
		$all_parts = $this->get_parts();

		foreach ( $all_parts as $part ) {
			if ( self::is_grid_part_active( $part['id'], 'gridd_grid' ) ) {
				$grid_parts[] = $part['id'];
			}
		}
		return apply_filters( 'gridd_active_grid_parts', $grid_parts );
	}

	/**
	 * Include files from grid-parts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function include_grid_part_files() {
		require_once get_theme_file_path( 'grid-parts/functions.php' );
	}

	/**
	 * Sets $this->parts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function set_parts() {
		$this->parts = apply_filters( 'gridd_get_template_parts', [] );

		// Reorder using the default priorities.
		usort(
			$this->parts,
			function( $a, $b ) {
				if ( isset( $a['priority'] ) && isset( $b['priority'] ) ) {
					return ( $a['priority'] > $b['priority'] ) ? 1 : -1;
				}
				return ( isset( $a['priority'] ) ) ? 1 : -1;
			}
		);
	}

	/**
	 * Get an array of template parts along with their labels.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_parts() {

		$order     = $this->get_smart_order();
		$unordered = [];
		$ordered   = [];
		// Add the ID as key. Makes next step easier/faster.
		foreach ( $this->parts as $part ) {
			$unordered[ $part['id'] ] = $part;
		}
		// Put the grid-parts in their right order.
		foreach ( $order as $item ) {
			if ( isset( $unordered[ $item ] ) ) {
				$ordered[] = $unordered[ $item ];
				unset( $unordered[ $item ] );
			}
		}
		// Add the remaining items.
		foreach ( $unordered as $item ) {
			$ordered[] = $item;
		}

		return apply_filters( 'gridd_get_parts', $this->parts );
	}

	/**
	 * Get an array of grid-parts.
	 * Returns the private $grid_parts property of this object.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_grid_parts() {
		$grid_parts = $this->get_parts();
		$parts      = [];

		foreach ( $grid_parts as $part ) {
			if ( isset( $part['id'] ) ) {
				$parts[] = $part['id'];
			}
		}
		return $parts;
	}

	/**
	 * Get the grids from parts.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_grids() {
		$parts = $this->get_parts();
		$grids = [];

		foreach ( $parts as $part ) {
			if ( isset( $part['grid'] ) ) {
				$grids[ $part['id'] ] = $part['grid'];
			}
		}
		return $grids;
	}

	/**
	 * Checks if a grid-part is active.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $grid_part The grid part we want to check.
	 * @param string $grid      The grid setting we want to check.
	 * @return bool
	 */
	public static function is_grid_part_active( $grid_part, $grid = 'gridd_grid' ) {
		$value = Grid::get_options( $grid );

		// If grid-part is hidden, it's still active.
		$parts = self::get_instance()->get_parts();
		foreach ( $parts as $key => $part ) {
			if ( isset( $part['id'] ) && $part['id'] === $grid_part && isset( $part['hidden'] ) && true === $part['hidden'] ) {
				return true;
			}
		}

		if ( isset( $value['areas'] ) ) {
			if ( isset( $value['areas'][ $grid_part ] ) && isset( $value['areas'][ $grid_part ]['cells'] ) && ! empty( $value['areas'][ $grid_part ]['cells'] ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the definition of a grid-part.
	 *
	 * @access protected
	 * @since 1.0.3
	 * @param string $id The grid-part ID.
	 * @return array|false
	 */
	protected function get_part_definition( $id ) {
		foreach ( $this->parts as $part ) {
			if ( $id === $part['id'] ) {
				return $part;
			}
		}
		return false;
	}

	/**
	 * Get the visual order of things.
	 * This will be used to reorder things in HTML.
	 *
	 * @access public
	 * @since 1.2.0
	 * @param string $theme_mod The grid we're referring to.
	 * @return array
	 */
	public function get_smart_order( $theme_mod = 'gridd_grid' ) {
		$value             = Grid::get_options( $theme_mod );
		$rtl               = is_rtl();
		$all_parts_ordered = 'gridd_grid' === $theme_mod ? [ 'header' ] : [];
		$grid              = [];

		if ( isset( $value['areas'] ) ) {
			foreach ( $value['areas'] as $part => $args ) {
				foreach ( $args['cells'] as $cell ) {
					$score = 1000 * $cell[0];
					$score += $rtl ? 100 - $cell[1] : $cell[1];
					$grid[] = [
						'id'     => $part,
						'score'  => $score,
						'row'    => $cell[0],
						'column' => $cell[1],
					];
				}
			}
		}

		if ( 'gridd_gridd' === $theme_mod ) {

			// Get the content columns.
			$content_columns = [];
			foreach ( $grid as $item ) {
				if ( 'content' === $item['id'] ) {
					$content_columns[] = $item['column'];
				}
			}

			// Get items that live in the main columns as the main content area.
			$main_area_items = [];
			foreach ( $grid as $item ) {
				if ( \in_array( $item['column'], $content_columns ) ) {
					$main_area_items[] = $item;
				}
			}

			// Sort items in the content-area columns.
			usort( $main_area_items, [ $this, 'compare_grid_cell_scores' ] );

			// Add items in the final array.
			foreach ( $main_area_items as $item ) {
				if ( ! \in_array( $item['id'], $all_parts_ordered ) ) {
					$all_parts_ordered[] = $item['id'];
				}
			}
		}

		// Sort items in all our grid-parts.
		usort( $grid, [ $this, 'compare_grid_cell_scores' ] );

		// Add items in the final array.
		foreach ( $grid as $item ) {
			if ( ! \in_array( $item['id'], $all_parts_ordered ) ) {
				$all_parts_ordered[] = $item['id'];
			}
		}

		// Get all grid-parts.
		if ( 'gridd_grid' === $theme_mod ) {
			$all_parts = $this->parts;

			$subgrids = [];
			foreach ( $all_parts as $part ) {
				if ( isset( $part['grid'] ) && \in_array( $part['id'], $all_parts_ordered ) ) {
					$subgrids[ $part['id'] ] = $this->get_smart_order( $part['grid'] );
				}
			}
		}

		$final = [];
		foreach ( $all_parts_ordered as $part ) {
			$final[] = $part;
			if ( isset( $subgrids[ $part ] ) ) {
				foreach ( $subgrids[ $part ] as $sub_part ) {
					$final[] = $sub_part;
				}
			}
		}

		// Multiply keys by 10 so we can inject other items when we need to.
		$final_multiplied = [];
		foreach ( $final as $k => $v ) {
			$final_multiplied[ 10 * $k ] = $v;
		}
		return apply_filters( 'gridd_smart_grid_parts_order', $final_multiplied );
	}

	/**
	 * Sort grid items by score.
	 *
	 * @access public
	 * @since @1.2.0
	 * @param array $a 1st item to compare.
	 * @param array $b 2nd item to compare.
	 * @return int
	 */
	public function compare_grid_cell_scores( $a, $b ) {
		if ( $a['score'] === $b['score'] ) {
			return 0;
		}
		return ( $a['score'] < $b['score'] ) ? -1 : 1;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

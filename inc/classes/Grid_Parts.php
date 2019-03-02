<?php
/**
 * The Grid_Parts class.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
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

		$grid_parts_paths = [];

		// Add child-theme first.
		if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/grid-parts' ) && is_dir( get_stylesheet_directory() . '/grid-parts' ) ) {
			$grid_parts_paths[] = get_stylesheet_directory() . '/grid-parts';
		}

		// Add parent theme.
		$grid_parts_paths[] = get_template_directory() . '/grid-parts';

		// Allow filtering to add grid-parts from plugins.
		$grid_parts_paths = apply_filters( 'gridd_grid_parts_paths', $grid_parts_paths );

		foreach ( $grid_parts_paths as $grid_parts_path ) {

			$parts = new \DirectoryIterator( $grid_parts_path );
			foreach ( $parts as $path ) {
				$path_parts = explode( '/', $path );
				$part       = $path_parts[ count( $path_parts ) - 1 ];
				if ( '..' === $part || '.' === $part ) {
					continue;
				}
				if ( file_exists( "$grid_parts_path/$part/class-$part.php" ) ) {
					require_once "$grid_parts_path/$part/class-$part.php";
				}
				if ( file_exists( "$grid_parts_path/$part/functions.php" ) ) {
					require_once "$grid_parts_path/$part/functions.php";
				}
			}
		}
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

		// Apply cusstom order.
		$this->apply_custom_order();
	}

	/**
	 * Get an array of template parts along with their labels.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_parts() {
		return $this->parts;
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
	 * Applies custom order to grid-parts.
	 *
	 * @access protected
	 * @since 1.0.3
	 * @return void
	 */
	protected function apply_custom_order() {
		$saved_order  = get_theme_mod( 'gridd_grid_load_order', array() );
		$all_part_ids = [];

		// Get an array of all part IDs.
		foreach ( $this->parts as $part ) {
			$all_part_ids[] = $part['id'];
		}

		// Add any missing part-IDs to the saved order.
		// Necessary if there is no saved order, or if a new part-ID was added.
		foreach ( $all_part_ids as $part_id ) {
			if ( ! in_array( $part_id, $saved_order ) ) {
				$saved_order[] = $part_id;
			}
		}

		// Build the ordered array.
		$ordered = [];
		foreach ( $saved_order as $part_id ) {
			$part = $this->get_part_definition( $part_id );
			if ( $part ) {
				$ordered[] = $part;
			}
		}

		// Update the array of parts with the custom-ordered one.
		$this->parts = $ordered;
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
}

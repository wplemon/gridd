<?php
/**
 * The Grid_Part class.
 * Individual grid-parts can extend this object to inherit its properties & methods.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Grid_Parts;

/**
 * Extra methods and actions for the blog.
 *
 * @since 1.0
 */
class Grid_Part extends Grid_Parts {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = '';

	/**
	 * The grid-part definition.
	 * Used by the add_template_part method.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $part = [];

	/**
	 * An array of files to include.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $include_files = [];

	/**
	 * The path to this directory..
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $dir = __DIR__;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		$this->set_part();
		$this->include_files();
		$this->init();
		add_filter( 'gridd_get_template_parts', [ $this, 'add_template_part' ] );
	}

	/**
	 * Extra functionality to run on init.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function init() {
		// Extend in child classes.
	}

	/**
	 * Includes extra files for this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function include_files() {
		foreach ( $this->include_files as $file ) {
			require_once $this->dir . '/' . $file;
		}
	}

	/**
	 * Set the part details.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {}

	/**
	 * Adds the grid-part to the array of grid-parts.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $parts The existing grid-parts.
	 * @return array
	 */
	public function add_template_part( $parts ) {

		// Early exit if the part is already defined.
		foreach ( $parts as $part ) {
			if ( $this->part['id'] === $part['id'] ) {
				return $parts;
			}
		}

		// Add the part.
		$parts[] = $this->part;
		return $parts;
	}

	/**
	 * Get the grid properties of a template part.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part.
	 * @param string $grid The grid setting value.
	 * @return array       An array of properties for this template-part.
	 */
	public static function format_specs_from_grid_value( $part, $grid ) {
		$specs = [
			'row'    => [],
			'column' => [],
		];

		$row_cells    = [];
		$column_cells = [];

		if ( isset( $grid['areas'] ) && isset( $grid['areas'][ $part ] ) && isset( $grid['areas'][ $part ]['cells'] ) ) {
			foreach ( $grid['areas'][ $part ]['cells'] as $cell ) {
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
		return $specs;
	}

	/**
	 * Get the grid properties of a template part.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $grid_part    The grid-part.
	 * @param string $grid_setting The grid setting. Necessary for nested grids to properly work.
	 * @return array               An array of properties for this template-part.
	 */
	public static function get_specs_from_grid_setting( $grid_part, $grid_setting = 'gridd_grid' ) {
		$grid = Grid::get_options( $grid_setting );
		return self::format_specs_from_grid_value( $grid_part, $grid );
	}

	/**
	 * Check if spec is valid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $spec The spec.
	 * @return bool
	 */
	public static function is_spec_valid( $spec ) {
		return ( is_array( $spec ) && isset( $spec['row'] ) && isset( $spec['column'] ) && ! empty( $spec['row'] ) && ! empty( $spec['column'] ) );
	}
}

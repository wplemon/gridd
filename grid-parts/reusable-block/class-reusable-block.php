<?php
/**
 * Gridd Reusable-Block grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\Style;

/**
 * The Gridd\Grid_Part\Reusable_Block object.
 *
 * @since 1.0
 */
class Reusable_Block extends Grid_Part {

	/**
	 * An array of files to include.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $include_files = [
		'customizer.php',
	];

	/**
	 * The path to this directory..
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $dir = __DIR__;

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
	}

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( 0 === strpos( $part, 'reusable_block_' ) && is_numeric( str_replace( 'reusable_block_', '', $part ) ) ) {
			$id = (int) str_replace( 'reusable_block_', '', $part );
			/**
			 * We use include( get_theme_file_path() ) here
			 * because we need to pass the $sidebar_id var to the template.
			 */
			include get_theme_file_path( 'grid-parts/reusable-block/template.php' );
		}
	}

	/**
	 * Adds the grid-part to the array of grid-parts.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $parts The existing grid-parts.
	 * @return array
	 */
	public function add_template_part( $parts ) {
		$number = self::get_number_of_reusable_blocks_grid_parts();
		for ( $i = 1; $i <= $number; $i++ ) {
			$parts[] = [
				/* translators: The number of the reusable block. */
				'label'    => sprintf( esc_html__( 'Reusable Block %d', 'gridd' ), absint( $i ) ),
				'color'    => [ '#000', '#fff' ],
				'priority' => 30,
				'id'       => "reusable_block_$i",
			];
		}
		return $parts;
	}

	/**
	 * Gets the number of reusable blocks.
	 * Returns the object's $number property.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_number_of_reusable_blocks_grid_parts() {
		return apply_filters( 'gridd_get_number_of_reusable_blocks_grid_parts', 2 );
	}
}


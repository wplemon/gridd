<?php
/**
 * Gridd Revolution_Slider grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 0.1
 */
class Revolution_Slider extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $id = 'revolution-slider';

	/**
	 * An array of files to include.
	 *
	 * @access protected
	 * @since 0.1
	 * @var array
	 */
	protected $include_files = [
		'customizer.php',
	];

	/**
	 * The path to this directory..
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $dir = __DIR__;

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function init() {
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
	}

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 0.1
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Revolution Slider', 'gridd' ),
			'color'    => [ '#EF6C00', '#000' ],
			'priority' => 200,
			'id'       => 'revolution-slider',
		];
	}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 0.1
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( $this->id === $part ) {
			get_template_part( 'grid-parts/revolution-slider/template' );
		}
	}
}

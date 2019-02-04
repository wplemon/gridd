<?php
/**
 * Gridd Layer_Slider grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 1.0
 */
class Layer_Slider extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'layer-slider';

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
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Layer Slider', 'gridd' ),
			'color'    => [ '#EF6C00', '#000' ],
			'priority' => 200,
			'id'       => 'layer-slider',
		];
	}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( $this->id === $part ) {
			get_template_part( 'grid-parts/layer-slider/template' );
		}
	}
}

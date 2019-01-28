<?php
/**
 * Gridd Header grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid;
use Gridd\Grid_Part;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 1.0
 */
class Header extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'header';

	/**
	 * An array of files to include.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $include_files = [
		'customizer.php',
		'customizer-branding.php',
		'customizer-contact-info.php',
		'customizer-social-media.php',
		'customizer-search.php',
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
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Header', 'gridd' ),
			'color'    => [ '#AD1457', '#fff' ],
			'priority' => 0,
			'id'       => $this->id,
			'grid'     => 'gridd_header_grid',
		];
	}

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		add_filter( 'gridd_get_grid_part_specs_header_contact_info', [ $this, 'get_grid_part_specs_header_contact_info' ] );
		add_filter( 'gridd_get_grid_part_specs_social_media', [ $this, 'get_grid_part_specs_social_media' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_filter( 'get_custom_logo', [ $this, 'get_custom_logo' ] );
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
			get_template_part( 'grid-parts/header/template' );
		}
	}

	/**
	 * Get the default value for the header grid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_grid_defaults() {
		return [
			'rows'         => 1,
			'columns'      => 2,
			'areas'        => [
				'header_branding' => [
					'cells' => [ [ 1, 1 ] ],
				],
				'nav_1'           => [
					'cells' => [ [ 1, 2 ] ],
				],
			],
			'gridTemplate' => [
				'rows'    => [ 'auto' ],
				'columns' => [ 'auto', 'auto' ],
			],
		];
	}

	/**
	 * Filter the grid-part specs.
	 * Necessary for pugged-in grid-parts with no dedicated templates.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $specs The initial specs.
	 * @return array
	 */
	public function get_grid_part_specs_header_contact_info( $specs ) {
		$grid = Grid::get_options( 'gridd_header_grid' );
		return self::format_specs_from_grid_value( 'header_contact_info', $grid );
	}

	/**
	 * Filter the grid-part specs.
	 * Necessary for pugged-in grid-parts with no dedicated templates.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $specs The initial specs.
	 * @return array
	 */
	public function get_grid_part_specs_social_media( $specs ) {
		$grid = Grid::get_options( 'gridd_header_grid' );
		return self::format_specs_from_grid_value( 'social_media', $grid );
	}

	/**
	 * Filter the get_custom_logo HTML and remove width & height.
	 * This fixes an issue on android devices where images don't get properly resized.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $html The logo HTML.
	 */
	public function get_custom_logo( $html ) {
		$html = \preg_replace( '/width="[0-9]*"/', '', $html );
		$html = \preg_replace( '/height="[0-9]*"/', '', $html );
		return $html;
	}
}

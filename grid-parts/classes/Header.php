<?php
/**
 * Gridd Header grid-part
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Grid;
use Gridd\Grid_Part;
use Gridd\Grid_Parts;

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
			gridd_get_template_part( 'grid-parts/templates/header' );
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

	/**
	 * Get an array of grid-parts for the header.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_header_grid_parts() {
		$header_grid_parts = Grid_Parts::get_instance()->get_parts();

		// Remove parts that are not valid in this sub-grid.
		$parts_to_remove = [ 'content', 'header', 'footer', 'nav-handheld', 'nested-grid-1', 'nested-grid-2', 'nested-grid-3', 'nested-grid-4' ];
		foreach ( $header_grid_parts as $key => $part ) {
			if ( isset( $part['id'] ) && in_array( $part['id'], $parts_to_remove, true ) ) {
				unset( $header_grid_parts[ $key ] );
			}
		}

		$header_grid_parts[] = [
			'label'    => esc_html__( 'Branding', 'gridd' ),
			'color'    => [ '#FFEB3B', '#000' ],
			'priority' => 0,
			'hidden'   => false,
			'id'       => 'header_branding',
		];

		$header_grid_parts[] = [
			'label'    => esc_html__( 'Search', 'gridd' ),
			'color'    => [ '#CFD8DC', '#000' ],
			'priority' => 200,
			'hidden'   => false,
			'id'       => 'header_search',
		];

		$header_grid_parts[] = [
			'label'    => esc_html__( 'Contact Information', 'gridd' ),
			'color'    => [ '#D4E157', '#000' ],
			'priority' => 1000,
			'hidden'   => false,
			'id'       => 'header_contact_info',
		];

		$header_grid_parts[] = [
			'label'    => esc_html__( 'Social Media', 'gridd' ),
			'color'    => [ '#009688', '#fff' ],
			'priority' => 2000,
			'hidden'   => false,
			'id'       => 'social_media',
		];

		return apply_filters( 'gridd_header_grid_parts', $header_grid_parts );
	}
}

new Header();
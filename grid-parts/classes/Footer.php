<?php
/**
 * Gridd Footer grid-part
 *
 * @package Gridd
 * 
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
use Gridd\Grid;
use Gridd\Grid_Parts;
use Gridd\Grid_Part;
use Gridd\Style;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 1.0
 */
class Footer extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'footer';

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Footer', 'gridd' ),
			'color'    => [ '#1565C0', '#fff' ],
			'priority' => 1000,
			'id'       => 'footer',
			'grid'     => 'gridd_footer_grid',
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
		add_action( 'widgets_init', [ $this, 'register_footer_sidebars' ], 30 );
		add_action( 'gridd_get_grid_part_specs_footer_social_media', [ $this, 'get_grid_part_specs_footer_social_media' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
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
			Theme::get_template_part( 'grid-parts/templates/footer' );
		}
	}

	/**
	 * Get the default value for the footer grid.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_grid_defaults() {
		return [
			'rows'         => 2,
			'columns'      => 3,
			'areas'        => [
				'footer_sidebar_1'    => [
					'cells' => [ [ 1, 1 ] ],
				],
				'footer_sidebar_2'    => [
					'cells' => [ [ 1, 2 ] ],
				],
				'footer_sidebar_3'    => [
					'cells' => [ [ 1, 3 ] ],
				],
				'footer_copyright'    => [
					'cells' => [ [ 2, 1 ], [ 2, 2 ] ],
				],
				'footer_social_media' => [
					'cells' => [ [ 2, 3 ] ],
				],
			],
			'gridTemplate' => [
				'rows'    => [ 'auto', 'auto' ],
				'columns' => [ '1fr', '1fr', '1fr', '1fr' ],
			],
		];
	}

	/**
	 * Register the sidebars.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function register_footer_sidebars() {

		$sidebars_nr = self::get_number_of_sidebars();
		for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
			register_sidebar(
				[
					/* translators: Sidebar number, */
					'name'          => sprintf( esc_attr__( 'Footer Widget Area %d', 'gridd' ), absint( $i ) ),
					'id'            => "footer_sidebar_$i",
					'description'   => esc_html__( 'Add widgets here.', 'gridd' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				]
			);
		}
	}

	/**
	 * Filter the grid-part specs.
	 * Necessary for pugged-in grid-parts with no dedicated templates.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_grid_part_specs_footer_social_media() {
		$grid = Grid::get_options( 'gridd_footer_grid' );
		return self::format_specs_from_grid_value( 'footer_social_media', $grid );
	}

	/**
	 * Gets the number of widget-areas in the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_number_of_sidebars() {
		return apply_filters( 'gridd_get_number_footer_sidebars', 6 );
	}

	/**
	 * Get an array of grid-parts for the footer.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_footer_grid_parts() {
		$footer_grid_parts = [
			[
				'label'    => esc_html__( 'Copyright Area', 'gridd' ),
				'color'    => [ '#A5D6A7', '#000' ],
				'priority' => 100,
				'hidden'   => false,
				'id'       => 'footer_copyright',
			],
			[
				'label'    => esc_html__( 'Social Media', 'gridd' ),
				'color'    => [ '#8BC34A', '#000' ],
				'priority' => 200,
				'hidden'   => false,
				'id'       => 'footer_social_media',
			],
		];

		$sidebars_nr = self::get_number_of_sidebars();
		for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
			$footer_grid_parts[] = [
				/* translators: The widget-area number. */
				'label'    => sprintf( esc_html__( 'Footer Widget Area %d', 'gridd' ), absint( $i ) ),
				'color'    => [ 'hsl(' . ( 55 * $i - 55 ) . ',57%,75%)', '#000' ],
				'priority' => 8 + $i * 2,
				'hidden'   => false,
				'class'    => "footer_sidebar_$i",
				'id'       => "footer_sidebar_$i",
			];
		}

		// Add reusable-block parts.
		$all_parts = Grid_Parts::get_instance()->get_parts();
		foreach ( $all_parts as $part ) {
			if ( isset( $part['id'] ) && 0 === strpos( $part['id'], 'reusable_block_' ) ) {
				$footer_grid_parts[] = $part;
			}
		}

		return apply_filters( 'gridd_footer_grid_parts', $footer_grid_parts );
	}
}

new Footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Gridd Nav_Handheld grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;

/**
 * The Gridd\Grid_Part\Nav_Handheld object.
 *
 * @since 1.0
 */
class Nav_Handheld extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'nav-handheld';

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
		add_action( 'widgets_init', [ $this, 'register_sidebar' ] );
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
			'label'    => esc_html__( 'Mobile Navigation', 'gridd' ),
			'color'    => [ '#FFB900', '#fff' ],
			'priority' => 50,
			'id'       => 'nav-handheld',
			'hidden'   => true,
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
			get_template_part( 'grid-parts/nav-handheld/template' );
		}
	}

	/**
	 * Add the widget area.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function register_sidebar() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Mobile Navigation Widget Area', 'gridd' ),
				'id'            => 'sidebar_handheld_widget_area',
				'description'   => esc_html__( 'Add widgets here.', 'gridd' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title h3">',
				'after_title'   => '</h2>',
			]
		);
	}
}

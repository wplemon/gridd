<?php
/**
 * Gridd Nav_Handheld grid-part
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
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
			Theme::get_template_part( 'grid-parts/templates/nav-handheld' );
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

new Nav_Handheld();
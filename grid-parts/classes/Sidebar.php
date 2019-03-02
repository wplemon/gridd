<?php
/**
 * Gridd Sidebar grid-part
 *
 * @package Gridd
 * 
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;

/**
 * The Gridd\Grid_Part\Sidebar object.
 *
 * @since 1.0
 */
class Sidebar extends Grid_Part {

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_action( 'get_sidebar', [ $this, 'get_sidebar' ] );
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
	 * Add action on the get_sidebar hook.
	 *
	 * @access public
	 * @since 1.0.3
	 * @param string $name The sidebar name.
	 * @return void
	 */
	public function get_sidebar( $name ) {
		$this->render( $name );
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
		if ( 0 === strpos( $part, 'sidebar_' ) && is_numeric( str_replace( 'sidebar_', '', $part ) ) ) {
			$sidebar_id = (int) str_replace( 'sidebar_', '', $part );
			/**
			 * We use include( get_theme_file_path() ) here
			 * because we need to pass the $sidebar_id var to the template.
			 */
			include get_theme_file_path( 'grid-parts/templates/sidebar.php' );
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
		$number = self::get_number_of_sidebars();
		for ( $i = 1; $i <= $number; $i++ ) {
			/* translators: The number of the widget area. */
			$label   = get_theme_mod( "gridd_grid_widget_area_{$i}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $i ) ) );
			$parts[] = [
				/* translators: The number of the navigation. */
				'label'    => $label,
				'color'    => [ 'hsl(' . ( 27 * $i + 150 ) . ',55%,40%)', '#fff' ],
				'priority' => 200 + $i,
				'id'       => "sidebar_$i",
			];
		}
		return $parts;
	}

	/**
	 * Output the CSS for a navigation.
	 *
	 * @access public
	 * @since 1.0
	 * @param int $id The navigation ID.
	 * @return void
	 */
	public function the_part_styles( $id ) {
	}

	/**
	 * Gets the number of sidebars.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_number_of_sidebars() {
		return apply_filters( 'gridd_get_number_of_nav_menus', 3 );
	}

	/**
	 * Register the sidebars.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function register_sidebars() {
		$number = self::get_number_of_sidebars();

		for ( $i = 1; $i <= $number; $i++ ) {

			/* translators: The number of the widget area. */
			$label = get_theme_mod( "gridd_grid_widget_area_{$i}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $i ) ) );

			register_sidebar(
				[
					'name'          => $label,
					'id'            => "sidebar-$i",
					'description'   => esc_html__( 'Add widgets here.', 'gridd' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title h3">',
					'after_title'   => '</h2>',
				]
			);
		}
	}
}

new Sidebar();
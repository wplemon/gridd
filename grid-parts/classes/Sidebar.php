<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd Sidebar grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\Rest;

/**
 * The Gridd\Grid_Part\Sidebar object.
 *
 * @since 1.0
 */
class Sidebar extends Grid_Part {

	/**
	 * Have the global styles already been added?
	 *
	 * @static
	 * @access public
	 * @since 1.0.8
	 * @var bool
	 */
	public static $global_styles_added = false;

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		$this->register_rest_api_partials();
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_action( 'get_sidebar', [ $this, 'get_sidebar' ] );
		add_action( 'gridd_the_partial', [ $this, 'the_partial' ] );
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
			if ( Rest::is_partial_deferred( $part ) ) {
				echo '<div class="gridd-tp gridd-tp-' . esc_attr( $part ) . ' gridd-rest-api-placeholder"></div>';
				return;
			}
			$this->the_partial( $part );
		}
	}

	/**
	 * Renders the grid-part partial.
	 *
	 * @access public
	 * @since 1.1
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function the_partial( $part ) {
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
		return apply_filters( 'gridd_get_number_of_widget_areas', 3 );
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

	/**
	 * Registers the partial(s) for the REST API.
	 *
	 * @access public
	 * @since 1.1
	 * @return void
	 */
	public function register_rest_api_partials() {
		$number = self::get_number_of_sidebars();

		for ( $i = 1; $i <= $number; $i++ ) {

			/* translators: The number of the widget area. */
			$label = get_theme_mod( "gridd_grid_widget_area_{$i}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $i ) ) );

			Rest::register_partial(
				[
					'id'    => "sidebar_$i",
					'label' => $label,
				]
			);
		}
	}
}

new Sidebar();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

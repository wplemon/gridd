<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd NavHandheld grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
use Gridd\Grid_Part;
use Gridd\Rest;

/**
 * The Gridd\Grid_Part\NavHandheld object.
 *
 * @since 1.0
 */
class NavHandheld extends Grid_Part {

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
		add_action( 'gridd_the_partial', [ $this, 'the_partial' ] );
		add_filter( 'gridd_smart_grid_main_parts_order', [ $this, 'grid_parts_order' ] );

		// Add script.
		add_filter( 'gridd_footer_inline_script_paths', [ $this, 'footer_inline_script_paths' ] );
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
		if ( $this->id !== $part ) {
			return;
		}
		if ( Rest::is_partial_deferred( $this->id ) ) {
			echo '<div class="gridd-tp gridd-tp-' . esc_attr( $this->id ) . ' gridd-rest-api-placeholder"></div>';
			return;
		}
		$this->the_partial( $this->id );
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
		if ( $this->id === $part ) {
			Theme::get_template_part( 'grid-parts/NavHandheld/template' );
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

	/**
	 * Adds the script to the footer.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $paths Paths to scripts we want to load.
	 * @return array
	 */
	public function footer_inline_script_paths( $paths ) {
		$paths[] = get_theme_file_path( 'grid-parts/NavHandheld/script.min.js' );
		return $paths;
	}

	/**
	 * Change the position of the grid-part and put it right after the header.
	 *
	 * @access public
	 * @since 2.0.0
	 * @param array $parts An array of our ordered grid-parts.
	 * @return array
	 */
	public function grid_parts_order( $parts ) {
		if ( ! get_theme_mod( 'gridd_grid_nav-handheld_enable', true ) ) {
			return $parts;
		}
		$final_parts = [];
		foreach ( $parts as $part ) {
			$final_parts[] = $part;
			if ( 'header' === $part ) {
				$final_parts[] = 'nav-handheld';
			}
		}

		return $final_parts;
	}
}

new NavHandheld();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Gridd Navigation grid-part
 *
 * @package Gridd
 * 
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\AMP;
use Gridd\Style;
use Gridd\Theme;

/**
 * The Gridd\Grid_Part\Navigation object.
 *
 * @since 1.0
 */
class Navigation extends Grid_Part {

	/**
	 * Whether the global navigation styles have been included or not.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var bool
	 */
	public static $global_styles_already_included = false;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'walker_nav_menu_start_el', [ $this, 'add_nav_sub_menu_buttons' ], 10, 2 );
		add_filter( 'wp_nav_menu_args', [ $this, 'nav_menu_args' ] );
	}

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'register_navigation_menus' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
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
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( 0 === strpos( $part, 'nav_' ) && is_numeric( str_replace( 'nav_', '', $part ) ) ) {
			$id = (int) str_replace( 'nav_', '', $part );
			/**
			 * We use include( get_theme_file_path() ) here
			 * because we need to pass the $sidebar_id var to the template.
			 */
			include get_theme_file_path( 'grid-parts/templates/navigation.php' );
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
		$number = self::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			$parts[] = [
				/* translators: The number of the navigation. */
				'label'    => sprintf( esc_html__( 'Navigation %d', 'gridd' ), absint( $i ) ),
				'color'    => [ '#0277BD', '#fff' ],
				'priority' => 30,
				'id'       => "nav_$i",
			];
		}
		return $parts;
	}

	/**
	 * Register the navigation.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function register_navigation_menus() {
		$number = self::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			register_nav_menus(
				[
					/* translators: The number of the navigation. */
					"menu-$i" => sprintf( esc_html__( 'Navigation %d', 'gridd' ), absint( $i ) ),
				]
			);
		}
	}

	/**
	 * Gets the global styles.
	 * We're checking if these have already been added
	 * and if they have, we return an empty string.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return string
	 */
	public static function get_global_styles() {
		if ( self::$global_styles_already_included ) {
			return '';
		}
		self::$global_styles_already_included = true;
		return Theme::get_fcontents( 'grid-parts/styles/navigation/styles-global.min.css' );
	}


	/**
	 * Gets the number of navigation menus.
	 * Returns the object's $number property.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 */
	public static function get_number_of_nav_menus() {
		return apply_filters( 'gridd_get_number_of_nav_menus', 3 );
	}

	/**
	 * Get SVG icons to expand menus.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_expand_svgs() {

		// IMPORTANT: Make sure all icons have a viewbox defined so that icons sizing can properly function.
		return apply_filters(
			'gridd_navigation_get_svgs',
			[
				'plus-1'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 9h-9v-9h-6v9h-9v6h9v9h6v-9h9z"/></svg>',
				'menu-1'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg>',
				'menu-11' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 18c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zm0-9c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zm0-9c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3z"/></svg>',
			]
		);
	}

	/**
	 * Get SVG icons to expand menus.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $icon The icon we use to expand the menu.
	 * @return string SVG Icon.
	 */
	public static function get_collapse_svg( $icon ) {
		return apply_filters(
			'gridd_navigation_get_close_svg',
			'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg>',
			$icon
		);
	}

	/**
	 * Filter the HTML output of a nav menu item to add the AMP dropdown button to reveal the sub-menu.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $item_output Nav menu item HTML.
	 * @param object $item        Nav menu item.
	 * @return string Modified nav menu item HTML.
	 */
	public function add_nav_sub_menu_buttons( $item_output, $item ) {

		// Skip when the item has no sub-menu.
		if ( ! in_array( 'menu-item-has-children', $item->classes, true ) ) {
			return $item_output;
		}

		// Generate a unique state ID.
		static $nav_menu_item_number = 0;
		$nav_menu_item_number++;
		$expanded_state_id = 'navMenuItemExpanded' . $nav_menu_item_number;

		return $item_output . Theme::get_toggle_button(
			[
				'context'                      => [ 'navigation', $item ],
				'expanded_state_id'            => $expanded_state_id,
				'expanded'                     => 'false',
				'screen_reader_label_collapse' => __( 'Collapse Child Menu', 'gridd' ),
				'screen_reader_label_expand'   => __( 'Expand Child Menu', 'gridd' ),
				'screen_reader_label_toggle'   => __( 'Toggle Child Menu', 'gridd' ),
				'label'                        => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M23.245 4l-11.245 14.374-11.219-14.374-.781.619 12 15.381 12-15.391-.755-.609z"/></svg>',
			]
		);
	}

	/**
	 * Tweak the nav-menu arguments.
	 *
	 * @access public
	 * @since 1.0.3
	 * @param array $args The arguments.
	 * @return array
	 */
	public function nav_menu_args( $args ) {
		$args['menu_class'] .= ' gridd-navigation';
		return $args;
	}
}

new Navigation();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

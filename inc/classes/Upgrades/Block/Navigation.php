<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Migrate arbitrary content to blocks
 *
 * @since 3.1.0
 * @package gridd
 */

namespace Gridd\Upgrades\Block;

/**
 * The block-migrator object.
 *
 * @since 3.1.0
 */
class Navigation extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The navigation location.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @var string
	 */
	protected $nav_location;

	/**
	 * The navigation orientation.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @var string
	 */
	protected $orientation = 'horizontal';

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param array $args The constructor arguments.
	 * @since 3.1.0
	 */
	public function __construct( $args ) {

		$this->set_object_params( $args );
		parent::__construct();
	}

	/**
	 * Set the object params.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @param array $args The arguments passed-on from the constructor.
	 * @return void
	 */
	public function set_object_params( $args ) {
		$this->nav_location = $args['nav_location'];
		$this->orientation  = $args['orientation'];
		$this->slug         = 'gridd-' . str_replace( '_', '-', $this->nav_location );
		$this->title        = 'Gridd: ' . $this->nav_location;
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @return string
	 */
	protected function get_content() {
		return $this->generate_block_from_menu( $this->nav_location, $this->orientation );
	}

	/**
	 * Generates block markup from a navigation menu.
	 *
	 * @access public
	 * @since 3.1.0
	 * @param string $nav_location The menu location.
	 * @param string $orienation   Can be horizontal|vertical.
	 * @return string Returns Block markup.
	 */
	public function generate_block_from_menu( $nav_location, $orientation = 'horizontal' ) {
		$menu       = false;
		$menu_items = false;
		$locations  = get_nav_menu_locations();
		if ( $locations && isset( $locations[ $nav_location ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $nav_location ] );
		}

		if ( ! $menu || is_wp_error( $menu ) ) {
			return '';
		}

		$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

		$sorted_menu_items        = array();
		$menu_items_with_children = array();
		foreach ( (array) $menu_items as $menu_item ) {
			$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
			if ( $menu_item->menu_item_parent ) {
				$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
			}
		}

		// Add the menu-item-has-children class where applicable.
		if ( $menu_items_with_children ) {
			foreach ( $sorted_menu_items as &$menu_item ) {
				if ( isset( $menu_items_with_children[ $menu_item->ID ] ) ) {
					$menu_item->classes[] = 'menu-item-has-children';
				}
			}
		}

		unset( $menu_items, $menu_item );

		/**
		 * Filters the sorted list of menu item objects before generating the menu's HTML.
		 *
		 * @since 3.1.0
		 *
		 * @param array    $sorted_menu_items The menu items, sorted by each menu item's menu order.
		 * @param stdClass $args              An object containing wp_nav_menu() arguments.
		 */
		$sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, [] );

		$child_of = '0';
		$prev_id  = 0;

		$block_markup = '<!-- wp:navigation {"orientation":"' . $orientation . '"} -->';
		foreach ( $sorted_menu_items as $menu_item ) {
			$prev_child_of = $child_of;
			$has_children  = ( in_array( 'menu-item-has-children', $menu_item->classes ) );
			$child_of      = $menu_item->menu_item_parent;

			if ( $prev_child_of !== $child_of && $child_of != $prev_id ) {
				$block_markup .= '<!-- /wp:navigation-link -->';
			}

			// Build the item args.
			$menu_item_args = [
				'label' => $menu_item->title,
				'type'  => $menu_item->type,
				'id'    => $menu_item->ID,
			];

			if ( $has_children ) {
				$block_markup .= '<!-- wp:navigation-link ' . wp_json_encode( $menu_item_args ) . ' -->';
			} else {
				$block_markup .= '<!-- wp:navigation-link ' . wp_json_encode( $menu_item_args ) . ' /-->';
			}

			$prev_id = $menu_item->ID;
		}
		$block_markup .= '<!-- /wp:navigation -->';
		return $block_markup;
	}

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access public
	 * @since 3.1.0
	 * @return bool
	 */
	public function should_migrate() {
		// Get the grids.
		$main_grid   = get_theme_mod( 'gridd_grid', [] );
		$header_grid = get_theme_mod( 'header_grid', \Gridd\Grid_Part\Header::get_grid_defaults() );
		$footer_grid = get_theme_mod( 'footer_grid', [] );

		if ( $main_grid && isset( $main_grid['areas'] ) && isset( $main_grid['areas'][ 'nav_' . str_replace( 'menu-', '', $this->nav_location ) ] ) ) {
			return true;
		}

		if ( $header_grid && isset( $header_grid['areas'] ) && isset( $header_grid['areas'][ 'nav_' . str_replace( 'menu-', '', $this->nav_location ) ] ) ) {
			return true;
		}

		if ( $footer_grid && isset( $footer_grid['areas'] ) && isset( $footer_grid['areas'][ 'nav_' . str_replace( 'menu-', '', $this->nav_location ) ] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Additional things to run after the block migration.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param int $block_id The block ID.
	 * @return void
	 */
	public function after_block_migration( $block_id ) {
		foreach ( [
			'gridd_grid'  => [],
			'header_grid' => \Gridd\Grid_Part\Header::get_grid_defaults(),
			'footer_grid' => []
		] as $theme_mod => $defaults ) {
			$grid      = get_theme_mod( 'header_grid', $defaults );
			$grid_part = str_replace( 'menu-', '', $this->nav_location );

			if ( ! isset( $grid['areas'] ) ) {
				return;
			}

			// Replace nav with the new, reusable block.
			$grid['areas'][ sanitize_key( 'reusable_block_' . $block_id ) ] = $grid['areas'][ $grid_part ];
			unset( $header_grid['areas'][ $grid_part ] );

			// Update the theme-mod.
			set_theme_mod( $theme_mod, $grid );
		}
	}
}

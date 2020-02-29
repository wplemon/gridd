<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Migrate arbitrary content to blocks
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades\Block;

/**
 * The block-migrator object.
 *
 * @since 3.0.0
 */
class Header_Search extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The block slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $slug = 'gridd-header-search';

	/**
	 * The block title.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $title = 'Gridd: Header Search';

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return bool
	 */
	protected function should_migrate() {

		// Get the footer grid.
		$header_grid = get_theme_mod( 'header_grid', \Gridd\Grid_Part\Header::get_grid_defaults() );

		// Check if we have a header-search part in our header grid.
		return ( $header_grid && isset( $header_grid['areas'] ) && isset( $header_grid['areas']['header_search'] ) );
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return string
	 */
	protected function get_content() {
		return '<!-- wp:search {"label":""} /-->';
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
		$header_grid = get_theme_mod( 'header_grid', \Gridd\Grid_Part\Header::get_grid_defaults() );

		// Replace footer-copyright with the new, reusable block.
		$header_grid['areas'][ sanitize_key( 'reusable_block_' . $block_id ) ] = $header_grid['areas']['header_search'];
		unset( $header_grid['areas']['header_search'] );

		// Update the header_grid theme-mod.
		set_theme_mod( 'header_grid', $header_grid );
	}
}

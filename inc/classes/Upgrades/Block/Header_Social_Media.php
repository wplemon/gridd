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
class Header_Social_Media extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The block slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $slug = 'gridd-header-social-media';

	/**
	 * The block title.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $title = 'Gridd: Header Social Media';

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return bool
	 */
	public function should_migrate() {

		// Get the header grid.
		$defaults    = get_theme_mod(
			'gridd_header_grid',
			[
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
			]
		);
		$header_grid = get_theme_mod( 'header_grid', $defaults );

		// Check if we have a header-social-media part in our header grid.
		return ( $header_grid && isset( $header_grid['areas'] ) && isset( $header_grid['areas']['social_media'] ) );
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return string
	 */
	protected function get_content() {

		$background_color = get_theme_mod( 'header_social_icons_background_color', '#ffffff' );
		$text_color       = get_theme_mod( 'header_social_icons_icons_color', '#000000' );

		$icons = get_theme_mod( 'header_social_icons', [] );

		$icons_html = '';
		foreach ( $icons as $icon ) {
			$icons_html .= '<!-- wp:social-link {"url":"' . $icon['url'] . '","service":"' . $icon['icon'] . '"} /-->';
		}

		$icons_html = '<!-- wp:social-links {"className":"is-style-logos-only"} -->
		<ul class="wp-block-social-links is-style-logos-only">' . $icons_html . '</ul><!-- /wp:social-links -->';

		// Get the template.
		ob_start();
		include __DIR__ . '/group-with-content.html';
		$final_content = ob_get_clean();

		// Replace placeholders with actual values.
		$final_content = str_replace( 'REPLACE_BACKGROUND_COLOR', esc_attr( $background_color ), $final_content );
		$final_content = str_replace( 'REPLACE_TEXT_COLOR', esc_attr( $text_color ), $final_content );
		$final_content = str_replace( 'REPLACE_CONTENT', $icons_html, $final_content );

		return $final_content;
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
		$defaults    = get_theme_mod(
			'gridd_header_grid',
			[
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
			]
		);
		$header_grid = get_theme_mod( 'header_grid', $defaults );

		// Replace footer-copyright with the new, reusable block.
		$header_grid['areas'][ sanitize_key( 'reusable_block_' . $block_id ) ] = $header_grid['areas']['social_media'];
		unset( $header_grid['areas']['social_media'] );

		// Update the header_grid theme-mod.
		set_theme_mod( 'header_grid', $header_grid );
	}
}

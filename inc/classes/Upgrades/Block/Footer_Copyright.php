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
class Footer_Copyright extends \Gridd\Upgrades\Block_Migrator {

	/**
	 * The block slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $slug = 'gridd-footer-copyright';

	/**
	 * The block title.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $title = 'Gridd: Footer Copyright';

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return bool
	 */
	protected function should_migrate() {

		// Get the footer grid.
		$defaults    = get_theme_mod(
			'gridd_footer_grid',
			[
				'rows'         => 2,
				'columns'      => 3,
				'areas'        => [
					'footer_sidebar_1' => [
						'cells' => [ [ 1, 1 ] ],
					],
					'footer_sidebar_2' => [
						'cells' => [ [ 1, 2 ] ],
					],
					'footer_sidebar_3' => [
						'cells' => [ [ 1, 3 ] ],
					],
					'footer_copyright' => [
						'cells' => [ [ 2, 1 ], [ 2, 2 ], [ 2, 3 ] ],
					],
				],
				'gridTemplate' => [
					'rows'    => [ 'auto', 'auto' ],
					'columns' => [ '1fr', '1fr', '1fr', '1fr' ],
				],
			]
		);
		$footer_grid = get_theme_mod( 'footer_grid', $defaults );

		// Check if we have a footer-copyright part in our footer grid.
		return ( $footer_grid && isset( $footer_grid['areas'] ) && isset( $footer_grid['areas']['footer_copyright'] ) );
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return string
	 */
	public function get_content() {

		$group_args = [
			'customBackgroundColor' => get_theme_mod( 'footer_copyright_bg_color', '#ffffff' ),
			'customTextColor'       => get_theme_mod( 'footer_copyright_color', '#000000' ),
			'align'                 => 'full',
		];

		$content  = '<!-- wp:group ' . wp_json_encode( $group_args ) . ' -->';
		$content .= '<div class="wp-block-group alignfull has-text-color has-background">';
		$content .= '<div class="wp-block-group__inner-container">';
		$content .= '<!-- wp:freeform -->';
		$content .= '<p style="text-align: center;">';
		$content .= get_theme_mod(
			'gridd_copyright_text',
			sprintf(
			/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
				__( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ),
				'<a href="https://wordpress.org/">WordPress</a>',
				'Gridd',
				'<a href="https://wplemon.com/">wplemon.com</a>'
			)
		);
		$content .= '</p>';
		$content .= '<!-- /wp:freeform -->';
		$content .= '</div></div><!-- /wp:group -->';
		return $content;
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
			'gridd_footer_grid',
			[
				'rows'         => 2,
				'columns'      => 3,
				'areas'        => [
					'footer_sidebar_1' => [
						'cells' => [ [ 1, 1 ] ],
					],
					'footer_sidebar_2' => [
						'cells' => [ [ 1, 2 ] ],
					],
					'footer_sidebar_3' => [
						'cells' => [ [ 1, 3 ] ],
					],
					'footer_copyright' => [
						'cells' => [ [ 2, 1 ], [ 2, 2 ], [ 2, 3 ] ],
					],
				],
				'gridTemplate' => [
					'rows'    => [ 'auto', 'auto' ],
					'columns' => [ '1fr', '1fr', '1fr', '1fr' ],
				],
			]
		);
		$footer_grid = get_theme_mod( 'footer_grid', $defaults );

		// Replace footer-copyright with the new, reusable block.
		$footer_grid['areas'][ sanitize_key( 'reusable_block_' . $block_id ) ] = $footer_grid['areas']['footer_copyright'];
		unset( $footer_grid['areas']['footer_copyright'] );

		// Update the footer_grid theme-mod.
		set_theme_mod( 'footer_grid', $footer_grid );
	}
}

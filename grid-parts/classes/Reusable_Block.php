<?php
/**
 * Gridd Reusable-Block grid-part
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\Style;

/**
 * The Gridd\Grid_Part\Reusable_Block object.
 *
 * @since 1.0
 */
class Reusable_Block extends Grid_Part {

	/**
	 * An array of reusable blocks.
	 *
	 * @static
	 * @access private
	 * @since 1.0.3
	 * @var array
	 */
	private static $reusable_blocks;

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		self::$reusable_blocks = get_posts(
			[
				'posts_per_page' => 100,
				'post_type'      => 'wp_block',
			]
		);
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_action( 'gridd_auto_text_color', [ $this, 'auto_text_color_settings' ] );
		add_filter( 'safe_style_css', [ $this, 'safe_style_css' ] );
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
		if ( 0 === strpos( $part, 'reusable_block_' ) && is_numeric( str_replace( 'reusable_block_', '', $part ) ) ) {
			$gridd_reusable_block_id = (int) str_replace( 'reusable_block_', '', $part );
			if ( apply_filters( 'gridd_render_grid_part', true, 'reusable_block_' . $gridd_reusable_block_id ) ) {
				/**
				 * We use include( get_theme_file_path() ) here
				 * because we need to pass the $gridd_reusable_block_id var to the template.
				 */
				include get_theme_file_path( 'grid-parts/templates/reusable-block.php' );
			}
		}
	}

	/**
	 * Adds the color settings to the array of auto-calculated settings for text-color.
	 *
	 * @access public
	 * @since 1.0.3
	 * @param array $settings The array of settings.
	 * @return array
	 */
	public function auto_text_color_settings( $settings ) {
		if ( self::$reusable_blocks ) {
			foreach ( self::$reusable_blocks as $block ) {
				$settings[ "gridd_grid_reusable_block_{$block->ID}_bg_color" ] = "gridd_grid_reusable_block_{$block->ID}_color";
			}
		}
		return $settings;
	}

	/**
	 * Get an array of reusable posts.
	 *
	 * @static
	 * @access public
	 * @since 1.0.3
	 * @return array
	 */
	public static function get_reusable_blocks() {
		return self::$reusable_blocks;
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

		if ( self::$reusable_blocks ) {
			$i = 0;
			foreach ( self::$reusable_blocks as $block ) {
				$parts[] = [
					/* translators: The name of the reusable block. */
					'label'    => sprintf( esc_html__( 'Block: %s', 'gridd' ), esc_html( $block->post_title ) ),
					'color'    => [ 'hsl(' . ( $i * 57 ) . ',50%,75%)', '#000' ],
					'priority' => 70,
					'id'       => "reusable_block_{$block->ID}",
				];
				$i++;
			}
		}

		return $parts;
	}

	/**
	 * Adds 'background-position' to the array of safe CSS rules.
	 * Fixes https://github.com/wplemon/gridd/issues/26
	 * The problem this solves is the fact that the WordPress Editor adds "background-position"
	 * in order to make the focal point work. However, wp_kses_post() strips it so we need to
	 * add it using a filter.
	 *
	 * @access public
	 * @since 1.0.8
	 * @param array $safe The params deemed safe.
	 * @return array
	 */
	public function safe_style_css( $safe ) {
		$safe[] = 'background-position';
		return $safe;
	}
}

new Reusable_Block();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

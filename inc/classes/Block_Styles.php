<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Print block styles.
 *
 * @package Gridd
 */

namespace Gridd;

/**
 * Template handler.
 *
 * @since 1.0
 */
class Block_Styles {

	/**
	 * An array of blocks used in this page.
	 *
	 * @static
	 * @access private
	 * @since 1.0.2
	 * @var array
	 */
	private static $block_styles_added = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );

		/**
		 * Use a filter to figure out which blocks are used.
		 * We'll use this to populate the $blocks property of this object
		 * and enque the CSS needed for them.
		 */
		add_filter( 'render_block', [ $this, 'render_block' ], 10, 2 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function scripts() {

		// Dequeue wp-core blocks styles. These will be added inline.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * Filters the content of a single block.
	 *
	 * @since 1.0.2
	 * @access public
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string               Returns $block_content unaltered.
	 */
	public function render_block( $block_content, $block ) {
		if ( $block['blockName'] ) {
			if ( ! in_array( $block['blockName'], self::$block_styles_added, true ) ) {
				self::$block_styles_added[] = $block['blockName'];

				$block_name  = str_replace( 'core/', '', $block['blockName'] );
				$styles_path = get_theme_file_path( "assets/css/blocks/$block_name.min.css" );
				if ( file_exists( get_theme_file_path( "assets/css/blocks/{$block['blockName']}.css" ) ) ) {
					$styles_path = get_theme_file_path( "assets/css/blocks/{$block['blockName']}.css" );
				}
				if ( file_exists( $styles_path ) ) {
					$block_content .= '<style id="gridd-block-styles-' . esc_attr( str_replace( '/', '-', $block['blockName'] ) ) . '">';
					// Not a remote URL, we can safely use file_get_contents.
					$block_content .= file_get_contents( $styles_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
					$block_content .= '</style>';
				}
			}
		}
		return $block_content;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

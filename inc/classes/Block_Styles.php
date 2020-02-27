<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Print block styles.
 *
 * @package Gridd
 * @since 3.0.0
 */

namespace Gridd;

/**
 * Template handler.
 *
 * @since 3.0.0
 */
class Block_Styles {

	/**
	 * An array of blocks used in this page.
	 *
	 * @static
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private static $block_styles_added = [];

	/**
	 * Constructor.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );

		/**
		 * Use a filter to figure out which blocks are used.
		 * We'll use this to populate the $blocks property of this object
		 * and enque the CSS needed for them.
		 */
		add_filter( 'render_block', [ $this, 'add_inline_styles' ], 10, 2 );
		add_filter( 'render_block', [ $this, 'convert_columns_to_grid' ], 10, 2 );
		add_filter( 'render_block', [ $this, 'cover_styles' ], 10, 2 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function scripts() {

		// Dequeue wp-core blocks styles. These will be added inline.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * Filters the content of a single block.
	 *
	 * Adds inline styles to blocks. Styles will only be added the 1st time we encounter the block.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string               Returns $block_content with our modifications.
	 */
	public function add_inline_styles( $block_content, $block ) {
		if ( $block['blockName'] ) {
			if ( ! in_array( $block['blockName'], self::$block_styles_added, true ) ) {
				self::$block_styles_added[] = $block['blockName'];

				$styles_path = get_theme_file_path( "assets/css/blocks/{$block['blockName']}.css" );
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

	/**
	 * Filters the content of a single block.
	 *
	 * Takes care of the "grid-template-columns" property for columns
	 * so we can properly use a css-grid layout instead of css-flexbox.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string               Returns $block_content with our modifications.
	 */
	public function convert_columns_to_grid( $block_content, $block ) {
		if ( 'core/columns' === $block['blockName'] ) {
			$grid_template_columns = [];
			foreach ( $block['innerBlocks'] as $column ) {
				$column_width = 'minmax(7em, 1fr)';
				if ( isset( $column['attrs'] ) && isset( $column['attrs']['width'] ) ) {
					$column_width = 'minmax(7em, ' . $column['attrs']['width'] . ')';
					if ( is_numeric( $column['attrs']['width'] ) ) {
						$column_width = 'minmax(7em, ' . $column['attrs']['width'] . 'fr)';
					}
				}
				$grid_template_columns[] = $column_width;
			}

			$block_content = str_replace(
				'class="wp-block-columns',
				'style="grid-template-columns:' . implode( ' ', $grid_template_columns ) . ';" class="wp-block-columns',
				$block_content
			);
		}
		return $block_content;
	}

	/**
	 * Filters the content of a single block.
	 *
	 * Add CSS-Variables to the cover block.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string               Returns $block_content with our modifications.
	 */
	public function cover_styles( $block_content, $block ) {
		if ( 'core/cover' === $block['blockName'] ) {
			$extra_styles = '';
			if ( isset( $block['attrs'] ) && isset( $block['attrs']['dimRatio'] ) ) {
				$extra_styles = '--dimRatio:' . ( absint( $block['attrs']['dimRatio'] ) / 100 ) . ';';
			}
			$block_content = str_replace(
				'style="',
				'style="' . $extra_styles,
				$block_content
			);
		}
		return $block_content;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

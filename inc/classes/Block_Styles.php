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
	 * A string containing all blocks styles
	 *
	 * @static
	 * @access private
	 * @since 3.0.0
	 * @var string
	 */
	private static $footer_block_styles = '';

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

		/**
		 * Add admin styles for blocks.
		 */
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );

		/**
		 * Add some styles in the footer.
		 */
		add_action( 'wp_footer', [ $this, 'footer_styles' ] );
	}

	/**
	 * Get an array of block styles.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return array
	 */
	public static function get_styled_blocks() {
		$block_styles = [
			'default'  => [],
			'override' => [],
		];

		$empty = [];

		// Iterate on default block styles.
		$iterator = new \DirectoryIterator( get_template_directory() . '/assets/css/blocks/defaults/core/' );
		foreach ( $iterator as $file_info ) {

			// Skip dot files.
			if ( $file_info->isDot() ) {
				continue;
			}

			// The block name.
			$block_name = str_replace( '.min', '', pathinfo( $file_info->getFilename() )['filename'] );

			// If the file is empty add to the $empty array.
			if ( 0 === filesize( $file_info->getPathname() ) ) {
				$empty[] = "core/$block_name";
			}

			// Add the block-name to our array.
			if ( ! strpos( $file_info->getFilename(), '.min.css' ) ) {
				$block_styles['default'][] = "core/$block_name";
			}
		}

		$block_styles['default'] = array_diff( $block_styles['default'], $empty );

		// Iterate on block styles overrides.
		$iterator = new \DirectoryIterator( get_template_directory() . '/assets/css/blocks/overrides/core/' );
		foreach ( $iterator as $file_info ) {

			// Skip dot files.
			if ( $file_info->isDot() ) {
				continue;
			}

			// The block name.
			$block_name = str_replace( '.min', '', pathinfo( $file_info->getFilename() )['filename'] );

			// Add the block-name to our array.
			if ( ! strpos( $file_info->getFilename(), '.min.css' ) ) {
				$block_styles['override'][] = "core/$block_name";
			}
		}

		return $block_styles;
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

				$defaults_path  = get_theme_file_path( "assets/css/blocks/defaults/{$block['blockName']}.min.css" );
				$overrides_path = get_theme_file_path( "assets/css/blocks/overrides/{$block['blockName']}.min.css" );

				ob_start();
				if ( file_exists( $defaults_path ) ) {
					include $defaults_path;
				}
				if ( file_exists( $overrides_path ) ) {
					include $overrides_path;
				}
				self::$footer_block_styles .= ob_get_clean();
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

	/**
	 * Enqueue block assets.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function enqueue_block_assets() {

		// We only need this in the editor.
		if ( ! is_admin() ) {
			return;
		}

		// Get an array of blocks.
		$blocks = self::get_styled_blocks();

		// Add blocks styles.
		foreach ( $blocks['default'] as $block ) {
			wp_enqueue_style(
				"gridd-$block-default",
				get_theme_file_uri( "assets/css/blocks/defaults/$block.min.css" ),
				[],
				GRIDD_VERSION
			);
		}
		foreach ( $blocks['override'] as $block ) {
			wp_enqueue_style(
				"gridd-$block-override",
				get_theme_file_uri( "assets/css/blocks/overrides/$block.min.css" ),
				[],
				GRIDD_VERSION
			);
		}
	}

	/**
	 * Print some styles in the footer.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function footer_styles() {
		echo '<style>' . esc_html( self::$footer_block_styles ) . '</style>';
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

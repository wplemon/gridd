<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Extra bits and pieces needed for the WordPress Editor.
 *
 * @package Gridd
 * @since 1.2.0
 */

namespace Gridd;

/**
 * Extra methods and actions for the editor.
 *
 * @since 1.2.0
 */
class Editor {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ], 1, 1 );
	}

	/**
	 * Add editor styles.
	 *
	 * @access public
	 * @since 1.2.0
	 * @return void
	 */
	public function block_editor_styles() {
		// wp_enqueue_style( 'gridd-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), $css_dependencies, wp_get_theme()->get( 'Version' ), 'all' );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

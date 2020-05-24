<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Extra bits and pieces needed for the WordPress Editor.
 *
 * @package Gridd
 * @since 2.0.0
 */

namespace Gridd;

/**
 * Extra methods and actions for the editor.
 *
 * @since 2.0.0
 */
class Editor {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_styles' ], 1, 1 );
	}

	/**
	 * Add editor styles.
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function block_editor_styles() {
		wp_enqueue_style( 'gridd-editor', get_template_directory_uri() . '/assets/css/admin/editor.min.css', [], GRIDD_VERSION );

		$styled_blocks = \Gridd\Block_Styles::get_styled_blocks();
		foreach ( $styled_blocks['default'] as $block ) {
			wp_enqueue_style( "gridd-editor-$block-default", get_template_directory_uri() . "/assets/css/blocks/defaults/$block.min.css", [], GRIDD_VERSION );
			if ( file_exists( get_theme_file_path( "assets/css/blocks/defaults/$block-editor.min.css" ) ) ) {
				wp_enqueue_style( "gridd-editor-$block-default-editor", get_template_directory_uri() . "/assets/css/blocks/defaults/$block-editor.min.css", [], GRIDD_VERSION );
			}
		}
		foreach ( $styled_blocks['override'] as $block ) {
			wp_enqueue_style( "gridd-editor-$block-override", get_template_directory_uri() . "/assets/css/blocks/overrides/$block.min.css", [], GRIDD_VERSION );
			if ( file_exists( get_theme_file_path( "assets/css/blocks/overrides/$block-editor.min.css" ) ) ) {
				wp_enqueue_style( "gridd-editor-$block-override-editor", get_template_directory_uri() . "/assets/css/blocks/overrides/$block-editor.min.css", [], GRIDD_VERSION );
			}
		}
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

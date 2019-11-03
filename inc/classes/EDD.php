<?php // phpcs:ignore WordPress.Files.FileName
/**
 * EDD Integration.
 *
 * @package Gridd
 */

namespace Gridd;

use Gridd\Theme;

/**
 * Add some hooks for Easy Digital Downloads.
 *
 * @since 1.0
 */
class EDD {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {

		// Early exit if EDD is not active.
		if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
			return;
		}

		// Remove and deactivate all styling included with EDD.
		remove_action( 'wp_enqueue_scripts', 'edd_register_styles' );

		$options = get_option( 'gridd_edd' );
		$options = wp_parse_args(
			$options,
			[
				'edd_after_download_content' => false,
			]
		);
		if ( $options['edd_after_download_content'] ) {
			remove_filter( 'the_content', 'edd_after_download_content' );
			$this->add_grid_part( 'edd_after_download_content' );
		}
	}

	/**
	 * Adds a grid part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $hook The hook we want to add.
	 * @return void
	 */
	public function add_grid_part( $hook ) {
		if ( 'edd_after_download_content' === $hook ) {
			add_filter(
				'gridd_get_template_parts',
				function( $parts ) {
					$parts[] = [
						'label'    => 'edd_after_download_content',
						'color'    => '#2794da',
						'priority' => 70,
						'id'       => 'edd_after_download_content',
						'class'    => 'gridd-edd-aft-dld-cnt',
					];
					return $parts;
				}
			);
			if ( function_exists( 'edd_after_download_content' ) ) {
				add_action(
					'gridd_get_template_part_edd_after_download_content',
					function() {
						global $post;
						if ( $post && 'download' === $post->post_type && is_singular( 'download' ) && is_main_query() && ! post_password_required() ) {
							do_action( 'edd_after_download_content', $post->ID );
						}
					}
				);
			}
		}
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

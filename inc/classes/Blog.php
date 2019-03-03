<?php
/**
 * Blog filters.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

/**
 * Extra methods and actions for the blog.
 *
 * @since 1.0
 */
class Blog {

	/**
	 * The post-parts.
	 * This is a static property used as a proxy to improve performance.
	 *
	 * @static
	 * @access private
	 * @since 1.0.3
	 * @var array
	 */
	private static $post_parts;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
	}

	/**
	 * Get the excerpt_more text.
	 *
	 * @access public
	 * @return string
	 */
	public function excerpt_more() {
		/* translators: %s: Name of current post. Only visible to screen readers */
		$read_more = get_theme_mod( 'gridd_excerpt_more', __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gridd' ) );
		if ( false !== strpos( $read_more, '%s' ) ) {
			$read_more = sprintf(
				$read_more,
				get_the_title(
					[
						'echo' => false,
					]
				)
			);
		}
		return ' <a href="' . esc_url_raw( get_the_permalink() ) . '">' . $read_more . '</a> ';
	}

	/**
	 * Returns an array of the singular parts for post, pages & CPTs.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public static function get_post_parts() {
		if ( ! self::$post_parts ) {
			self::$post_parts = apply_filters(
				'gridd_get_post_parts',
				[
					'post-title',
					'post-date-author',
					'post-thumbnail',
					'post-content',
					'post-category',
					'post-tags',
					'post-comments-link'
				]
			);
		}
		return self::$post_parts;
	}
}

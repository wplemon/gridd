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
 * @since 0.1
 */
class Blog {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 0.1
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
}

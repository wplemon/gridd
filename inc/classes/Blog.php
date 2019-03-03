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
		add_filter( 'comment_form_defaults', [ $this, 'comment_form_defaults' ] );
		add_filter( 'wp_list_categories', [ $this, 'list_categories' ] );
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
	 * Add classes to the comment-form submit button.
	 *
	 * @since 1.0
	 * @param array $args The comment-form args.
	 * @return array
	 */
	function comment_form_defaults( $args ) {
		$args['class_submit'] .= ' wp-block-button__link';
		return $args;
	}

	/**
	 * Move categories counts inside the links.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $html The links.
	 * @return string
	 */
	function list_categories( $html ) {
		return str_replace(
			[ '</a> (', ')' ],
			[ ' (', ')</a>' ],
			$html
		);
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

	/**
	 * Comments link.
	 * 
	 * @static
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public static function the_comments_link() {
		comments_popup_link(
			sprintf(
				/* translators: %s: post title */
				'<span class="screen-reader-text">' . esc_html__( 'Leave a Comment on %s', 'gridd' ) . '</span>',
				get_the_title()
			)
		);
	}

	/**
	 * Post-edit link.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public static function the_edit_link() {
		edit_post_link(
			sprintf(
				/* translators: %s: Name of the post.*/
				esc_html__( 'Edit %s', 'gridd' ),
				'<span class="screen-reader-text">' . get_the_title() . '</span>'
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

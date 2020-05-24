<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Blog filters.
 *
 * @package Gridd
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
		add_filter( 'gridd_get_post_parts', [ $this, 'gridd_get_post_parts_filter' ] );
		add_action( 'gridd_get_template_part', [ $this, 'hide_title_on_pages' ], 10, 2 );
		add_filter( 'post_class', [ $this, 'archives_post_mode_class' ] );
	}

	/**
	 * Get the excerpt_more text.
	 *
	 * @access public
	 * @return string
	 */
	public function excerpt_more() {
		/* translators: %s: Name of current post. Only visible to screen readers */
		$read_more = get_theme_mod( 'excerpt_read_more', __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gridd' ) );
		if ( false !== strpos( $read_more, '%s' ) ) {
			$read_more = sprintf(
				$read_more,
				get_the_title( get_the_ID() )
			);
		}
		return ' <a href="' . esc_url_raw( get_the_permalink() ) . '">' . wp_kses_post( $read_more ) . '</a> ';
	}

	/**
	 * Add classes to the comment-form submit button.
	 *
	 * @since 1.0
	 * @param array $args The comment-form args.
	 * @return array
	 */
	public function comment_form_defaults( $args ) {
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
	public function list_categories( $html ) {
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
			$defaults = [
				'post-title',
				'post-date-author',
				'post-thumbnail',
				'post-content',
				'post-category',
				'post-tags',
				'post-comments-link',
			];

			if ( is_singular() ) {
				$defaults = [
					'post-thumbnail',
					'post-title',
					'post-date-author',
					'post-content',
					'post-category',
					'post-tags',
					'post-comments-link',
				];
			}

			self::$post_parts = apply_filters( 'gridd_get_post_parts', $defaults );
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
		$visible = '<span aria-hidden="true"><span class="label">' . esc_html__( 'Leave a Comment', 'gridd' ) . '</span></span>';

		$invisible = sprintf(
			/* translators: %s: post title */
			'<span class="screen-reader-text">' . esc_html__( 'Leave a Comment on %s', 'gridd' ) . '</span>',
			get_the_title()
		);

		comments_popup_link( $visible . $invisible );
	}

	/**
	 * Post-edit link.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param null|int|WP_Post $post_id The post.
	 * @param bool             $echo    Set to true if you want to echo instead of return.
	 * @return string
	 */
	public static function get_the_edit_link( $post_id = null, $echo = false ) {
		if ( get_edit_post_link( $post_id ) ) {
			$text  = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-5 17l1.006-4.036 3.106 3.105-4.112.931zm5.16-1.879l-3.202-3.202 5.841-5.919 3.201 3.2-5.84 5.921z"/></svg>';
			$text .= '<span class="screen-reader-text">';
			$text .= sprintf(
				/* translators: %s: Name of the post.*/
				esc_html__( 'Edit %s', 'gridd' ),
				get_the_title( $post_id )
			);
			$text .= '</span>';

			if ( $echo ) {
				edit_post_link( $text, '<span class="edit-link">', '</span>', $post_id );
				return;
			}

			ob_start();
			edit_post_link( $text, '<span class="edit-link">', '</span>', $post_id );
			return ob_get_clean();
		}
		return '';
	}

	/**
	 * Filters the parts that will be loaded for posts.
	 *
	 * @access public
	 * @since 1.0.4
	 * @param array $parts The parts to load.
	 * @return array
	 */
	public function gridd_get_post_parts_filter( $parts ) {
		if ( is_singular() ) {
			if ( 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' ) && ! is_page() ) {
				$new_parts = [
					'post-featured-image-header',
				];
				foreach ( $parts as $part ) {
					if ( 'post-title' !== $part && 'post-date-author' !== $part && 'post-thumbnail' !== $part ) {
						$new_parts[] = $part;
					}
				}
				$parts = $new_parts;
			}
			$post_format = get_post_format();
			if ( 'aside' === $post_format || 'link' === $post_format || 'quote' === $post_format || 'status' === $post_format ) {
				$remove_parts = [ 'post-category', 'post-tags', 'post-date-author', 'part-post-comments-link' ];
				foreach ( $remove_parts as $remove_part ) {
					$key = array_search( $remove_part, $parts, true );
					if ( false !== $key ) {
						unset( $parts[ $key ] );
					}
				}
			}
			if ( is_page() ) {
				$key = array_search( 'post-date-author', $parts, true );
				if ( false !== $key ) {
					unset( $parts[ $key ] );
				}
			}
		}
		return $parts;
	}

	/**
	 * Hides the title on the frontpage.
	 *
	 * @access public
	 * @since 1.1.1
	 * @param string|false $custom_path The custom template-part path. Defaults to false. Use absolute path.
	 * @param string       $slug        The template slug.
	 * @param string       $name        The template name.
	 * @see https://developer.wordpress.org/reference/functions/get_template_part/
	 * @return string|bool
	 */
	public static function hide_title_on_pages( $custom_path, $slug, $name = null ) {
		return 'template-parts/part-post-title' === $slug && \is_front_page() && ! \is_home() && \get_theme_mod( 'gridd_hide_home_title', true ) ? true : $custom_path;
	}

	/**
	 * Adds extra classes on <article> tags using the "post_class" filter.
	 *
	 * @access public
	 * @since 2.0.0
	 * @param array $classes The classes for this element.
	 * @return array
	 */
	public function archives_post_mode_class( $classes ) {
		if ( is_archive() || is_home() ) {
			$classes[] = 'gridd-post-mode-' . get_theme_mod( 'archive_post_mode', 'default' );
		}
		return $classes;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

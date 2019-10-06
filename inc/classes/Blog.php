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
		$visible = sprintf(
			/* translators: The icon. */
			'<span aria-hidden="true" style="display:flex;"><span class="label">' . esc_html__( '%s Leave a Comment', 'gridd' ) . '</span></span>',
			'<svg class="gridd-comment-icon" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M20 15c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1m-3 0c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1m-3 0c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1m5.415 4.946c-1 .256-1.989.482-3.324.482-3.465 0-7.091-2.065-7.091-5.423 0-3.128 3.14-5.672 7-5.672 3.844 0 7 2.542 7 5.672 0 1.591-.646 2.527-1.481 3.527l.839 2.686-2.943-1.272zm-13.373-3.375l-4.389 1.896 1.256-4.012c-1.121-1.341-1.909-2.665-1.909-4.699 0-4.277 4.262-7.756 9.5-7.756 5.018 0 9.128 3.194 9.467 7.222-1.19-.566-2.551-.889-3.967-.889-4.199 0-8 2.797-8 6.672 0 .712.147 1.4.411 2.049-.953-.126-1.546-.272-2.369-.483m17.958-1.566c0-2.172-1.199-4.015-3.002-5.21l.002-.039c0-5.086-4.988-8.756-10.5-8.756-5.546 0-10.5 3.698-10.5 8.756 0 1.794.646 3.556 1.791 4.922l-1.744 5.572 6.078-2.625c.982.253 1.932.407 2.85.489 1.317 1.953 3.876 3.314 7.116 3.314 1.019 0 2.105-.135 3.242-.428l4.631 2-1.328-4.245c.871-1.042 1.364-2.384 1.364-3.75"/></svg>'
		);

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
	 * @since 1.1.19
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

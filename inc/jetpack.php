<?php
/**
 * Jetpack Compatibility File
 *
 * @package Gridd
 * @link https://jetpack.com/
 *
 * phpcs:ignoreFile WordPress.Files.FileName.InvalidClassFileName
 */

namespace Gridd;

/**
 * Adds Jetpack-setup methods.
 *
 * @since 0.1
 */
class Jetpack {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 *
	 * Jetpack setup function.
	 *
	 * @access public
	 * @since 0.1
	 * @see https://jetpack.com/support/infinite-scroll/
	 * @see https://jetpack.com/support/responsive-videos/
	 * @see https://jetpack.com/support/content-options/
	 * @return void
	 */
	public function setup() {
		// Add theme support for Infinite Scroll.
		add_theme_support(
			'infinite-scroll',
			[
				'container' => 'main',
				'render'    => [ $this, 'infinite_scroll_render' ],
				'footer'    => 'page',
			]
		);

		/**
		 * Add theme support for Responsive Videos.
		 */
		add_theme_support( 'jetpack-responsive-videos' );

		// Add theme support for Content Options.
		add_theme_support(
			'jetpack-content-options',
			[
				'post-details'    => [
					'stylesheet' => 'gridd-style',
					'date'       => '.posted-on',
					'categories' => '.cat-links',
					'tags'       => '.tags-links',
					'author'     => '.byline',
					'comment'    => '.comments-link',
				],
				'featured-images' => [
					'archive' => true,
					'post'    => true,
					'page'    => true,
				],
			]
		);
	}

	/**
	 * Custom render function for Infinite Scroll.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				get_template_part( 'template-parts/content', 'search' );
			else :
				get_template_part( 'template-parts/content', get_post_format() );
			endif;
		}
	}
}

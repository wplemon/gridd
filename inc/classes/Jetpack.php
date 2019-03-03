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

use Gridd\Theme;

/**
 * Adds Jetpack-setup methods.
 *
 * @since 1.0
 */
class Jetpack {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 *
	 * Jetpack setup function.
	 *
	 * @access public
	 * @since 1.0
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
				'type'      => 'click',
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
	 * @since 1.0
	 */
	public function infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				Theme::get_template_part( 'template-parts/content', 'search' );
			else :
				Theme::get_template_part( 'template-parts/content', get_post_format() );
			endif;
		}
	}

	/**
	 * Enqueue & Dequeue scripts & styles.
	 *
	 * @access public
	 * @since 1.0.3
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_dequeue_style( 'the-neverending-homepage' );
	}
}

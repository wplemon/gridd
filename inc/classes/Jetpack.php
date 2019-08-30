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
use Gridd\Color;
use Gridd\Customizer\Sanitize;

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
		add_action( 'wp_footer', [ $this,'css_vars' ] );
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

		// Add support for the Tonesque library.
		add_theme_support( 'tonesque' );
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

	/**
	 * Output some extra theme-mods in <head> if needed.
	 *
	 * @access public
	 * @since 1.0.4
	 * @return void
	 */
	public function css_vars() {

		// Make sure we have everything we need.
		if ( get_theme_mod( 'gridd_featured_image_overlay_color_from_image', true ) && class_exists( '\Tonesque' ) ) {

			// Get the thumbnail-id.
			$thumbnail_id = get_post_thumbnail_id();

			// Early exit if we don't have a thumbnail.
			if ( ! $thumbnail_id ) {
				return;
			}

			// Get saved color from the attachment's post-meta.
			$image_color = get_post_meta( $thumbnail_id, '_gridd_image_color', true );

			// If the color was not found, create it and save.
			if ( ! $image_color ) {

				// Get the URL.
				$image = get_the_post_thumbnail_url();
				if ( $image ) {

					// Get the image's color using Jetpack's Tonesque library.
					$tonesque    = new \Tonesque( $image );
					$image_color = $tonesque->color();

					// Update the attachment's post-meta.
					// We're using post-meta to improve performance and avoid getting the color every single time.
					$sanitize = new Sanitize();
					update_post_meta( $thumbnail_id, '_gridd_image_color', $sanitize->color_hex( $image_color ) );
				}
			}

			// If we have a color, add our custom css-vars.
			if ( $image_color ) {
				$color_obj = \ariColor::newColor( $image_color );

				// Consistent lightness for all images.
				$color_obj = $color_obj->getNew( 'lightness', 22 );

				// If this is not a black/white image, increase the saturation.
				if ( 2 < $color_obj->saturation ) {
					$color_obj = $color_obj->getNew( 'saturation', 75 );
				}
				echo '<style>:root{--im-hoc:' . esc_attr( $color_obj->getNew( 'alpha', .85 )->toCSS( 'rgba' ) ) . ';--im-htc:#fff;}</style>';
			}
		}
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

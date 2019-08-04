<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Style;

/**
 * The WooCommerce class.
 *
 * @since 1.0
 */
class WooCommerce {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {

		// Early exit if WooCommerce does not exist.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Add theme supports.
		add_action( 'after_setup_theme', [ $this, 'add_theme_supports' ] );

		// Add inline styles.
		add_action( 'wp_footer', [ $this, 'inline_styles' ] );

		// Add body classes.
		add_filter( 'body_class', [ $this, 'body_class' ] );

		// Products per page filter.
		add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );

		// Columns filters.
		add_filter( 'woocommerce_single_product_image_gallery_classes', [ $this, 'single_product_image_gallery_classes' ] );
		add_filter( 'loop_shop_columns', '__return_zero' );

		// Related Products.
		add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );

		// Main Content Wrappers.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', [ $this, 'wrapper_before' ] );
		add_action( 'woocommerce_after_main_content', [ $this, 'wrapper_after' ] );

		// Remove Breadcrumbs.
		add_action( 'init', [ $this, 'remove_breadcrumbs' ] );

		if ( AMP::is_active() ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
			add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_cart_fragments' ], 11 );
		}

		// Hide shop page title if it's the frontpage and we don't want titles there.
		add_action( 'woocommerce_page_title', [ $this, 'front_shop_page_title' ] );
	}

	/**
	 * Add theme supports for WooCommerce.
	 *
	 * @access public
	 * @since 1.0
	 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
	 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
	 * @return void
	 */
	public function add_theme_supports() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Add inline styles for WooCommerce.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function inline_styles() {

		$style = Style::get_instance( 'woocommerce' );

		// Add the main woo styles.
		$style->add_file( get_theme_file_path( 'assets/css/plugins/woocommerce.min.css' ) );

		// Account page styles.
		if ( ! function_exists( 'is_account_page' ) || is_account_page() ) {
			$style->add_file( get_theme_file_path( '/assets/css/plugins/woo-account.min.css' ) );
		}

		// Cart styles.
		if ( ! function_exists( 'is_cart' ) || is_cart() ) {
			$style->add_file( get_theme_file_path( '/assets/css/plugins/woo-cart.min.css' ) );
		}

		// AMP.
		if ( AMP::is_active() ) {
			$style->add_file( get_theme_file_path( '/assets/css/plugins/amp-woo.min.css' ) );
		}

		$style->add_vars(
			[
				'--gridd-woo-catalog-product-min-width' => get_theme_mod( 'gridd_woocommerce_product_catalog_min_width', '250px' ),
				'--gridd-typo-scale'                    => get_theme_mod( 'gridd_type_scale', 1.26 ),
				'--gridd-text-color'                    => get_theme_mod( 'gridd_text_color', '#000000' ),
				'--gridd-links-color'                   => get_theme_mod( 'gridd_links_color', '#005ea5' ),
				'--gridd-links-hover-color'             => get_theme_mod( 'gridd_links_hover_color', '#2900a3' ),
			]
		);
		
		$style->the_css( 'gridd-inline-css-wc' );
	}

	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @access public
	 * @since 1.0
	 * @param  array $classes CSS classes applied to the body tag.
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	public function body_class( $classes ) {
		$classes[] = 'woocommerce-active';
		return $classes;
	}

	/**
	 * Products per page.
	 *
	 * @access public
	 * @since 1.0
	 * @return integer number of products.
	 */
	public function products_per_page() {
		return (int) get_theme_mod( 'gridd_woocommerce_product_catalog_per_page', 12 );
	}

	/**
	 * Remove the WooCommerce default BreadCrumbs.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function remove_breadcrumbs() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}

	/**
	 * Related Products Args.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $args related products args.
	 * @return array $args related products args.
	 */
	public function related_products_args( $args ) {
		return wp_parse_args(
			[
				'posts_per_page' => 3,
				'columns'        => false,
			],
			$args
		);
	}

	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function wrapper_before() {
		echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
	}

	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function wrapper_after() {
		echo '</main>'; // Close #main.
		echo '</div>'; // Close #primary.
	}

	/**
	 * Dequeue cart fragments, therefore disabling AJAX calls from WooCommerce.
	 * We're only doing this when AMP is enabled.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function dequeue_cart_fragments() {
		wp_dequeue_script( 'wc-cart-fragments' );
	}

	/**
	 * Hide shop page title if it's the frontpage and we don't want titles there.
	 *
	 * @access public
	 * @since 1.1.1
	 * @param string $page_title The title.
	 * @return string
	 */
	public function front_shop_page_title( $page_title ) {
		return ( is_shop() && (int) get_option( 'page_on_front' ) === (int) wc_get_page_id( 'shop' ) ) ? '' : $page_title;
	}

	/**
	 * Filters image-gallery classes for single products.
	 *
	 * @access public
	 * @since 1.1.11
	 * @param array $classes An array of CSS classes for the element.
	 * @return array
	 */
	public function single_product_image_gallery_classes( $classes ) {
		global $product;
		$attachment_ids    = $product->get_gallery_image_ids();
		$post_thumbnail_id = $product->get_image_id();

		$total_image_count = $post_thumbnail_id ? 1 : 0;
		if ( $attachment_ids && is_array( $attachment_ids ) ) {
			$total_image_count += count( $attachment_ids );
		}

		$classes[] = 'gridd-woo-gallery-image-count-' . $total_image_count;
		return $classes;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

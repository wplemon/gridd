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
		add_filter( 'woocommerce_product_thumbnails_columns', '__return_false' );
		add_filter( 'loop_shop_columns', '__return_zero' );

		// Related Products.
		add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );

		// Main Content Wrappers.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', [ $this, 'wrapper_before' ] );
		add_action( 'woocommerce_after_main_content', [ $this, 'wrapper_after' ] );

		// AJAX Cart.
		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_link_fragment' ] );

		// Remove Breadcrumbs.
		add_action( 'init', [ $this, 'remove_breadcrumbs' ] );

		if ( AMP::is_active() ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
			add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_cart_fragments' ], 11 );
		}
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

		$style->add_vars(
			[
				'--gridd-woo-catalog-product-min-width' => get_theme_mod( 'gridd_woocommerce_product_catalog_min_width', '250px' ),
				'--gridd-typo-scale'                    => get_theme_mod( 'gridd_type_scale', 1.333 ),
				'--gridd-text-color'                    => get_theme_mod( 'gridd_text_color', '#000000' ),
				'--gridd-links-color'                   => get_theme_mod( 'gridd_links_color', '#005ea5' ),
				'--gridd-links-hover-color'             => get_theme_mod( 'gridd_links_hover_color', '#2900a3' ),
			]
		);

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
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	public function cart_link_fragment( $fragments ) {
		ob_start();
		$this->cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}

	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'gridd' ); ?>">
			<svg class="gridd-inline-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm6.304-15l-3.431 12h-2.102l2.542-9h-16.813l4.615 11h13.239l3.474-12h1.929l.743-2h-4.196z"/></svg>
			<?php
			// Items count.
			$count = WC()->cart->get_cart_contents_count();
			// Count text.
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', $count, 'gridd' ),
				$count
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<?php if ( $count ) : ?>
				<span class="count" aria-label="<?php echo esc_html( $item_count_text ); ?>"><?php echo absint( $count ); ?></span>
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * Display Header Cart.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function header_cart() {
		$class = ( is_cart() ) ? 'current-menu-item' : '';
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php $this->cart_link(); ?>
			</li>
			<li>
				<?php
				the_widget(
					'WC_Widget_Cart',
					[
						'title' => '',
					]
				);
				?>
			</li>
		</ul>
		<?php
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
}

<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$label_class = get_theme_mod( 'gridd_grid_nav-handheld_hide_labels', false ) ? 'screen-reader-text' : 'label';

$count_styles = '';
$label        = '<span class="' . esc_attr( $label_class ) . '">';
$label       .= esc_html__( 'Cart', 'gridd' );
$label       .= '<div class="count">' . WC()->cart->get_cart_contents_count() . '</div>';
$label       .= '</span>';
?>
<nav id="gridd-handheld-woo-cart">
	<?php
	/**
	 * Prints the button.
	 * No need to escape this, it's already escaped in the function itself.
	 */
	echo gridd_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		[
			'expanded_state_id' => 'griddHandheldWooCartWrapper',
			'expanded'          => 'false',
			/* translators: number of items in the cart. */
			'label'             => '<svg class="gridd-inline-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm6.304-15l-3.431 12h-2.102l2.542-9h-16.813l4.615 11h13.239l3.474-12h1.929l.743-2h-4.196z"/></svg>' . $label,
			'classes'           => [ 'gridd-nav-handheld-btn' ],
		]
	);
	?>
	<div id="gridd-handheld-cart-wrapper" class="gridd-hanheld-woo-popup-wrapper">
		<?php
		the_widget(
			'WC_Widget_Cart',
			[
				'title' => '',
			]
		);
		?>
	</div>
</nav>

<?php
/**
 * EDD template for each item in the cart.
 *
 * @package Gridd
 */

?>
<li class="edd-cart-item">
	<span class="edd-cart-item-title">{item_title}</span>
	<span><?php echo edd_item_quantities_enabled() ? '<span class="edd-cart-item-quantity">{item_quantity}&nbsp;@&nbsp;</span>' : ''; ?></span>
	<span class="edd-cart-item-price">{item_amount}</span>
	<a href="{remove_url}" data-cart-item="{cart_item_id}" data-download-id="{item_id}" data-action="edd_remove_from_cart" class="edd-remove-from-cart">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M12.12 10l3.53 3.53-2.12 2.12L10 12.12l-3.54 3.54-2.12-2.12L7.88 10 4.34 6.46l2.12-2.12L10 7.88l3.54-3.53 2.12 2.12z"/></g></svg>
		<span class="screen-reader-text"><?php esc_html_e( 'Remove', 'gridd' ); ?></span>
	</a>
</li>

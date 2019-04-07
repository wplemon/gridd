<?php
/**
 * Single Product title
 *
 * Overrides the default WooCommerce title template for single products
 * and adds the edit link if user has permissions to edit the product.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Gridd
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Gridd\Blog;

the_title( '<h1 class="product_title entry-title">', Blog::get_the_edit_link() . '</h1>' );

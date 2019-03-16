<?php
/**
 * Template part for the previous/next links in posts & pages.
 *
 * @package Gridd
 * @since 1.0
 */

if ( ! get_theme_mod( 'gridd_show_next_prev', true ) ) {
	return;
}
wp_link_pages(
	[
		'before'    => '<div class="page-links"><span class="label">' . esc_html__( 'Pages:', 'gridd' ) . '</span><span class="item">',
		'after'     => '</span></div>',
		'separator' => '</span><span class="item">',
	]
);

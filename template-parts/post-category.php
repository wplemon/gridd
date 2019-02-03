<?php
/**
 * Template part for displaying the post-category(ies).
 *
 * @package Gridd
 * @since 0.1
 */

/* translators: used between list items, there is a space after the comma */
$categories_list = get_the_category_list( esc_html__( ', ', 'gridd' ) );
if ( $categories_list ) {
	echo '<span class="cat-links">';
	/* translators: 1: list of categories. */
	printf( esc_html__( 'Posted in %1$s', 'gridd' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</span>';
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

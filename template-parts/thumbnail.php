<?php
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @package Gridd
 * @since 0.1
 */

if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
	return;
}

if ( is_singular() ) {
	get_template_part( 'template-parts/thumbnail-singular', get_post_type( $post->ID ) );
} else {
	get_template_part( 'template-parts/thumbnail-archive', get_post_type( $post->ID ) );
}

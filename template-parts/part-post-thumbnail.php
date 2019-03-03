<?php
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
	return;
}

if ( is_singular() ) {
	Theme::get_template_part( 'template-parts/thumbnail-singular', get_post_type( $post->ID ) );
} else {
	Theme::get_template_part( 'template-parts/thumbnail-archive', get_post_type( $post->ID ) );
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

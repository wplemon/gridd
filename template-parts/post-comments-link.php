<?php
/**
 * Template part for displaying the post's comments link.
 * Only used in post-archives.
 *
 * @package Gridd
 * @since 1.0
 */

if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	echo '<span class="comments-link">';
	gridd_the_comments_link();
	echo '</span>';
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 0.1
 */

if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	echo '<span class="comments-link">';
	gridd_the_comments_link();
	echo '</span>';
}
gridd_the_edit_link();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

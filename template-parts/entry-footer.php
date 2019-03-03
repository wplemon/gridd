<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Blog;

if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	echo '<span class="comments-link">';
	Blog::the_comments_link();
	echo '</span>';
}
Blog::the_edit_link();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

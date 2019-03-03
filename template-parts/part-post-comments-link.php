<?php
/**
 * Template part for displaying the post's comments link.
 * Only used in post-archives.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Blog;

if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
	?>
	<div class="entry-comments-link gridd-contain">
		<span class="comments-link"><?php Blog::the_comments_link(); ?></span>
	</div>
	<?php
endif;

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

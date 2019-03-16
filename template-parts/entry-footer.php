<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Blog;

?>
<?php if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
	<span class="comments-link">
		<?php Blog::the_comments_link(); ?>
	</span>
<?php endif; ?>

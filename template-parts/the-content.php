<?php
/**
 * The main content.
 *
 * @package Gridd
 * @since 1.0
 */

// Content for the "Quote" post-format.
if ( get_post_format() && in_array( get_post_format(), [ 'quote', 'link', 'video', 'image', 'audio', 'aside' ], true ) ) {
	the_content();
	return;
}

if ( ! is_singular() ) {
	if ( ! get_theme_mod( 'gridd_archives_display_full_post', false ) ) {
		echo '<div class="gridd-excerpt-container">';
		the_excerpt();
		echo '</div>';
		return;
	}
	the_content( gridd()->blog->excerpt_more() );
	return;
}
the_content();

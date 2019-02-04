<?php
/**
 * Template part for displaying the post-tags.
 *
 * @package Gridd
 * @since 1.0
 */

$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'gridd' ) );
if ( $tags_list ) {
	echo '<span class="tags-links">';
	printf(
		/* translators: 1: list of tags. */
		esc_html__( 'Tagged %1$s', 'gridd' ),
		wp_kses_post( $tags_list )
	);
	echo '</span>';
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

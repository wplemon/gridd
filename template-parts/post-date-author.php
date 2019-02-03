<?php
/**
 * Template part for displaying the post-time & byline.
 *
 * @package Gridd
 * @since 0.1
 */

?>

<div class="entry-meta">
	<span class="posted-on">
		<?php
		printf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'gridd' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . sprintf(
				( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) ? '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>' : '<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			) . '</a>'
		);
		?>
	</span>
	<span class="byline">
		<?php
		printf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'gridd' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		?>
	</span>
</div>

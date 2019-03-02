<?php
/**
 * Template part for displaying posts - "Chat" post-format.
 *
 * @package Gridd
 * @since 1.0
 */

$parts = gridd_get_post_parts();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	foreach ( $parts as $part ) {
		if ( in_array( $part, [ 'post-title', 'post-thumbnail', 'post-content', 'post-category', 'post-tags', 'post-date-author', 'part-post-comments-link' ], true ) ) {
			$part_name = is_archive() ? 'archive' : null;
			$part_name = is_singular() ? 'singular' : null;
			gridd_get_template_part( 'template-parts/part-' . $part, $part_name );
		}
	}
	?>
	<footer class="entry-footer container">
		<?php gridd_get_template_part( 'template-parts/entry-footer', get_post_type( $post->ID ) ); ?>
	</footer>
</article>

<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Blog;

$parts = Blog::get_post_parts();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	foreach ( $parts as $part ) {
		if ( in_array( $part, [ 'post-title', 'post-thumbnail', 'post-content' ], true ) ) {
			$part_name = is_archive() ? 'archive' : null;
			$part_name = is_singular() ? 'singular' : null;
			gridd_get_template_part( 'template-parts/part-' . $part, $part_name );
		}
	}
	?>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer container">
			<?php gridd_the_edit_link(); ?>
		</footer>
	<?php endif; ?>
</article>

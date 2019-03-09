<?php
/**
 * Template part for displaying posts - "Chat" post-format.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Blog;

$parts = Blog::get_post_parts();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	foreach ( $parts as $part ) {
		$part_name = is_archive() ? 'archive' : null;
		$part_name = is_singular() ? 'singular' : null;
		Theme::get_template_part( 'template-parts/part-' . $part, $part_name );
	}
	?>
	<footer class="entry-footer container">
		<?php Theme::get_template_part( 'template-parts/entry-footer', get_post_type( $post->ID ) ); ?>
	</footer>
</article>

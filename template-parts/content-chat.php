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
	<?php foreach ( $parts as $part ) : ?>
		<?php Theme::get_template_part( 'template-parts/part-' . $part ); ?>
	<?php endforeach; ?>
</article>

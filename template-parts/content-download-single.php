<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Blog;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>">
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', Blog::get_the_edit_link() . '</h1>' ); ?>
	</header>

	<?php Theme::get_template_part( 'template-parts/thumbnail-download' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>

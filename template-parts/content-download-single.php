<?php
/**
 * Template part for displaying posts
 *
 * @package Gridd
 * @since 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<?php gridd_get_template_part( 'template-parts/thumbnail', get_post_type( $post->ID ) ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>

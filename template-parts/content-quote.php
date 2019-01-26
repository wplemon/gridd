<?php
/**
 * Template part for displaying posts - "Quote" post-format.
 *
 * @package Gridd
 * @since 1.0
 */

$parts = gridd_get_post_parts();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php foreach ( $parts as $part ) : ?>

		<?php
		/**
		 * Title.
		 */
		?>
		<?php if ( 'post-title' === $part ) : ?>
			<header class="entry-header">
				<div class="container">
					<?php if ( is_singular() ) : ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php else : ?>
						<?php the_title( '<h2 class="entry-title h5"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
					<?php endif; ?>
				</div>
			</header>
		<?php endif; ?>

		<?php
		/**
		 * Thumbnail.
		 */
		?>
		<?php if ( 'post-thumbnail' === $part ) : ?>
			<?php get_template_part( 'template-parts/thumbnail', get_post_type( $post->ID ) ); ?>
		<?php endif; ?>

		<?php
		/**
		 * Content.
		 */
		?>
		<?php if ( 'post-content' === $part ) : ?>
			<div class="entry-content container">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</article>

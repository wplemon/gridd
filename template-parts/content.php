<?php
/**
 * Template part for displaying posts
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
						<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
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
				<?php get_template_part( 'template-parts/the-content' ); ?>
				<?php get_template_part( 'template-parts/link-pages' ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Category.
		 */
		?>
		<?php if ( 'post-category' === $part ) : ?>
			<div class="entry-category gridd-contain">
				<?php get_template_part( 'template-parts/post-category' ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Tags.
		 */
		?>
		<?php if ( 'post-tags' === $part ) : ?>
			<div class="entry-tags gridd-contain">
				<?php get_template_part( 'template-parts/post-tags' ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Date & Byline.
		 */
		?>
		<?php if ( 'post-date-author' === $part ) : ?>
			<div class="entry-date-author gridd-contain">
				<?php get_template_part( 'template-parts/post-date-author' ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Comments Link.
		 */
		?>
		<?php if ( 'post-comments-link' === $part ) : ?>
			<div class="entry-comments-link gridd-contain">
				<?php get_template_part( 'template-parts/post-comments-link' ); ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<footer class="entry-footer container">
		<?php get_template_part( 'template-parts/entry-footer', get_post_type( $post->ID ) ); ?>
	</footer>
</article>

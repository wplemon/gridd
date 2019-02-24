<?php
/**
 * Template part for displaying page content in page.php
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
			<header class="entry-header container">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>
		<?php endif; ?>

		<?php if ( 'post-thumbnail' === $part ) : ?>
			<?php gridd_get_template_part( 'template-parts/thumbnail', get_post_type( $post->ID ) ); ?>
		<?php endif; ?>

		<?php if ( 'post-content' === $part ) : ?>
			<div class="entry-content container">
				<?php the_content(); ?>
				<?php gridd_get_template_part( 'template-parts/link-pages' ); ?>
			</div>
		<?php endif; ?>

	<?php endforeach; ?>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer container">
			<?php gridd_the_edit_link(); ?>
		</footer>
	<?php endif; ?>
</article>

<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Gridd
 */

use Gridd\Theme;
?>
<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
	<header class="page-header container">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</header>
	<div class="gridd-archive-downloads">
		<?php while ( have_posts() ) : ?>
			<?php
			the_post();
			$arg = get_post_type( $post->ID );
			if ( 'post' === get_post_type( $post->ID ) ) {
				$arg = get_post_format();
			};

			/*
			* Include the Post-Format-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Format name or he post-type) and that will be used instead.
			*/
			Theme::get_template_part( 'template-parts/content', $arg );
			?>
		<?php endwhile; ?>
	</div>
	<?php the_posts_navigation(); ?>
<?php else : ?>
	<?php Theme::get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

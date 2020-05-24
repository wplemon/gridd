<?php
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/core/featured-image-post-header.min.css' ) );
?>
<?php if ( has_post_thumbnail() ) : ?>
	<div class="gridd-featured-image-post-header">
		<div class="gridd-featured-image-post-header-image-wrapper"><?php the_post_thumbnail(); ?></div>
		<div class="gridd-featured-image-post-header-overlay"></div>
		<div class="gridd-featured-image-post-header-content">
			<div class="inner">
<?php endif; ?>
				<?php Theme::get_template_part( 'template-parts/part-post-title', get_post_type( $post->ID ) ); ?>
				<?php Theme::get_template_part( 'template-parts/part-post-date-author', get_post_type( $post->ID ) ); ?>

<?php if ( has_post_thumbnail() ) : ?>
			</div>
		</div>
	</div>
<?php endif; ?>

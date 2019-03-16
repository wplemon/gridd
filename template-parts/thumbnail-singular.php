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

if ( 'hidden' === get_theme_mod( 'gridd_featured_image_mode_singular', 'alignwide' ) ) {
	return;
}
?>

<?php if ( apply_filters( 'gridd_featured_image_use_background', false ) ) : ?>
	<div class="post-thumbnail <?php echo esc_attr( get_theme_mod( 'gridd_featured_image_mode_singular', 'alignwide' ) ); ?>" style="background-image:url(<?php the_post_thumbnail_url(); ?>);"></div>
<?php else : ?>
	<div class="post-thumbnail <?php echo esc_attr( get_theme_mod( 'gridd_featured_image_mode_singular', 'alignwide' ) ); ?>">
		<?php the_post_thumbnail(); ?>
	</div>
<?php endif; ?>

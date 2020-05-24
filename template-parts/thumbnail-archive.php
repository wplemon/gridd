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

if ( 'hidden' === get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' ) && 'default' === get_theme_mod( 'archive_post_mode', 'default' ) ) {
	return;
}
$gridd_thumbnail_class = ( 'card' === get_theme_mod( 'archive_post_mode', 'default' ) ) ? 'alignwide' : get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' );
?>

<a class="post-thumbnail <?php echo esc_attr( $gridd_thumbnail_class ); ?>" href="<?php the_permalink(); ?>" aria-hidden="true">
	<?php
	the_post_thumbnail(
		'post-thumbnail',
		[
			'alt' => the_title_attribute(
				[
					'echo' => false,
				]
			),
		]
	);
	?>
</a>

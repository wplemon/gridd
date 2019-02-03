<?php
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @package Gridd
 * @since 0.1
 */

if ( 'hidden' === get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' ) ) {
	return;
}
?>

<a class="post-thumbnail <?php echo esc_attr( get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' ) ); ?>" href="<?php the_permalink(); ?>" aria-hidden="true">
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

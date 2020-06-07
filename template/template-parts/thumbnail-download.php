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

use Gridd\Image;

if ( 'hidden' === get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' ) ) {
	return;
}
?>

<a class="post-thumbnail <?php echo esc_attr( get_theme_mod( 'gridd_featured_image_mode_archive', 'alignwide' ) ); ?>" href="<?php the_permalink(); ?>" aria-hidden="true">
	<?php
	$ratio = get_theme_mod( 'gridd_edd_product_grid_image_ratio', 'golden' );
	$width = absint( get_theme_mod( 'gridd_edd_grid_min_col_width', 15 ) * get_theme_mod( 'gridd_body_font_size', 18 ) * ( 1 + 2.5 * get_theme_mod( 'gridd_fluid_typography_ratio', 0.25 ) ) );

	// If we're on the customizer, use a hardcoded width of 320 to prevent constantly regenerating images.
	if ( is_customize_preview() ) {
		$width = 320;
	}

	// Double the width to accomodate Hi-Res screens.
	$width *= 2;

	switch ( $ratio ) {
		case '1:1':
		case '4:3':
		case 'golden':
			// Calculate the height.
			$height = (int) ( $width / 1.618 );
			if ( '1:1' === $ratio ) {
				$height = $width;
			} elseif ( '4:3' === $ratio ) {
				$height = (int) ( $width * 0.75 );
			}

			// Get the thumbnail-ID.
			$thumbnail_id = get_post_thumbnail_id();
			if ( $thumbnail_id ) {

				// Create the image.
				$image  = Image::create( $thumbnail_id );
				$resize = $image->resize(
					[
						'width'  => $width,
						'height' => $height,
					]
				);
				echo $resize['url'] ? '<img src="' . esc_url( $resize['url'] ) . '">' : '';
			}
			break;
		default:
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
	}
	?>
</a>

<?php
/**
 * Template part for Revolution Slider
 *
 * @package Gridd
 * @since 1.0
 */

$wrapper_class = apply_filters( 'gridd_grid_part_class', 'gridd-tp gridd-tp-revolution-slider', 'revolution-slider' );
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php

	// Get the selected slider.
	$slide = get_theme_mod( 'gridd_grid_revslider_slider', '' );

	// Print the slider.
	if ( $slide ) {
		echo do_shortcode( '[rev_slider alias="' . esc_html( $slide ) . '"]' );
	}
	?>
</div>

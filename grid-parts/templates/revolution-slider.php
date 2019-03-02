<?php
/**
 * Template part for Revolution Slider
 *
 * @package Gridd
 * @since 1.0
 */

?>
<div class="gridd-tp gridd-tp-revolution-slider">
	<?php

	// Get the selected slider.
	$slide = get_theme_mod( 'gridd_grid_revslider_slider', '' );

	// Print the slider.
	if ( $slide ) {
		echo do_shortcode( '[rev_slider alias="' . esc_html( $slide ) . '"]' );
	}
	?>
</div>

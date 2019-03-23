<?php
/**
 * Template part for Layer Slider
 *
 * @package Gridd
 * @since 1.0
 */

$wrapper_class = apply_filters( 'gridd_grid_part_class', 'gridd-tp gridd-tp-revolution-slider', 'revolution-slider' );
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php

	// Get the selected slider.
	$slide = get_theme_mod( 'gridd_grid_layerslider_slider', '' );

	// Print the slider.
	if ( $slide && function_exists( 'layerslider' ) ) {
		layerslider( $slide );
	}
	?>
</div>

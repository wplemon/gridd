<?php
/**
 * Template part for Layer Slider
 *
 * @package Gridd
 * @since 1.0
 */

?>
<div class="gridd-tp-revolution-slider gridd-tp">
	<?php

	// Get the selected slider.
	$slide = get_theme_mod( 'gridd_grid_layerslider_slider', '' );

	// Print the slider.
	if ( $slide && function_exists( 'layerslider' ) ) {
		layerslider( $slide );
	}
	?>
</div>

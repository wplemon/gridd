<?php
/**
 * Template part for Layer Slider
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
?>
<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-layer-slider' ], 'wrapper-layer-slider' ); ?>>
	<?php

	// Get the selected slider.
	$slide = get_theme_mod( 'gridd_grid_layerslider_slider', '' );

	// Print the slider.
	if ( $slide && function_exists( 'layerslider' ) ) {
		layerslider( $slide );
	}
	?>
</div>

<?php
/**
 * Init Revolution_Slider.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Revolution_Slider;

if ( ! class_exists( 'RevSliderSlider' ) ) {
	return;
}

new Revolution_Slider();

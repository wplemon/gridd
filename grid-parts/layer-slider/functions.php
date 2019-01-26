<?php
/**
 * Init Later_Slider.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Layer_Slider;

if ( ! class_exists( 'LS_Sliders' ) ) {
	return;
}

new Layer_Slider();

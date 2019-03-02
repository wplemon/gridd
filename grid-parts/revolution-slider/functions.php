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

require_once 'Revolution_Slider.php';
new Revolution_Slider();

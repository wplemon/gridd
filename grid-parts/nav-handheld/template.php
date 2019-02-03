<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 0.1
 */

use Gridd\AMP;
use Gridd\Style;

// Add styles.
$style = Style::get_instance( 'grid-part/nav-handheld' );
$style->add_file( get_theme_file_path( 'grid-parts/nav-handheld/styles/styles.min.css' ) );
$style->add_string( '@media only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '800px' ) . '){.gridd-tp.gridd-tp-nav-handheld{display: none;}body{padding-bottom:0;}}' );
?>
<div class="gridd-tp gridd-tp-nav-handheld">
	<?php
	if ( get_theme_mod( 'gridd_grid_nav-handheld_enable', true ) ) {

		// Get the array of parts we want to show.
		$default = class_exists( 'WooCommerce' ) ? [ 'menu', 'home', 'search', 'woo-cart' ] : [ 'menu', 'home', 'search' ];
		$parts   = get_theme_mod( 'gridd_grid_nav-handheld_parts', $default );

		// Check that we want to show something.
		if ( ! empty( $parts ) ) {

			// Print styles.
			$style->the_css( 'gridd-inline-css-nav-handheld' );

			// Print the parts.
			foreach ( $parts as $part ) {
				get_template_part( 'grid-parts/nav-handheld/part', $part );
				do_action( 'gridd_nav_handheld_part_template', $part );
			}
		}
	}
	?>
</div>

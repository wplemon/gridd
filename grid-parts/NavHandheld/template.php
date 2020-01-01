<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

// Add styles.
\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/NavHandheld/styles.min.css' ) );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-nav-handheld hide-on-large' ], 'wrapper-nav-handheld' ); ?>>
	<?php
	if ( get_theme_mod( 'nav-handheld_enable', true ) ) {

		// Get the array of parts we want to show.
		$default = class_exists( 'WooCommerce' ) ? [ 'menu', 'home', 'search', 'woo-cart' ] : [ 'menu', 'home', 'search' ];
		$parts   = get_theme_mod( 'nav-handheld_parts', $default );

		// Check that we want to show something.
		if ( ! empty( $parts ) ) {

			// Print the parts.
			foreach ( $parts as $part ) {
				Theme::get_template_part( "grid-parts/NavHandheld/template-$part" );
				do_action( 'gridd_nav_handheld_part_template', $part );
			}
		}
	}
	?>
</div>

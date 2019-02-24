<?php
/**
 * Template part for the Header.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid;
use Gridd\Grid_Part\Header;
use Gridd\Style;

// Get the grid settings.
$settings = Grid::get_options( 'gridd_header_grid', Header::get_grid_defaults() );

// Add styles.
$style = Style::get_instance( 'grid-part/header' );
$style->add_string(
	Grid::get_styles_responsive(
		[
			'context'    => 'header',
			'large'      => Grid::get_options( 'gridd_header_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '992px' ),
			'selector'   => '.gridd-tp-header > .inner',
			'prefix'     => true,
		]
	)
);
$style->add_file( get_theme_file_path( 'grid-parts/header/styles/default.min.css' ) );
$style->add_string( '@media only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){' );
$style->add_file( get_theme_file_path( 'grid-parts/header/styles/large.min.css' ) );
$style->add_string( '}' );
if ( true === get_theme_mod( 'gridd_header_sticky', false ) && false === get_theme_mod( 'gridd_header_sticky_mobile', false ) ) {
	$style->add_string( '@media only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){.gridd-tp.gridd-tp-header.gridd-sticky{position:relative;}}' );
}
$style->add_vars(
	[
		'--gridd-header-bg'         => get_theme_mod( 'gridd_grid_part_details_header_background_color', '#ffffff' ),
		'--gridd-header-box-shadow' => get_theme_mod( 'gridd_grid_header_box_shadow', '0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)' ),
		'--gridd-header-max-width'  => get_theme_mod( 'gridd_grid_header_max_width', '100%' ),
		'--gridd-header-grid-gap'   => get_theme_mod( 'gridd_grid_header_grid_gap', '0' ),
	]
);

// Get the header image.
$styles        = '';
$header_bg_img = get_header_image();
if ( $header_bg_img ) {
	// If we have a header image, add it as a background.
	$style->add_string( '.gridd-tp.gridd-tp-header{background-image:url(\'' . esc_url_raw( $header_bg_img ) . '\');background-size:cover;background-position:center center;}' );
}
?>
<div class="gridd-tp gridd-tp-header<?php echo get_theme_mod( 'gridd_header_sticky', false ) ? ' gridd-sticky' : ''; ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-header' );
	?>
	<div class="inner">
		<?php
		if ( isset( $settings['areas'] ) ) {
			foreach ( array_keys( $settings['areas'] ) as $part ) {
				if ( 'header_branding' === $part ) {
					/**
					 * Branding.
					 */
					gridd_get_template_part( 'grid-parts/header/template-branding' );

				} elseif ( 'header_search' === $part ) {
					/**
					 * Search.
					 */
					gridd_get_template_part( 'grid-parts/header/template-search' );

				} elseif ( 'header_contact_info' === $part ) {
					/**
					 * Contact Info.
					 */
					gridd_get_template_part( 'grid-parts/header/template-contact-info' );

				} elseif ( 'social_media' === $part ) {
					/**
					 * Social Media.
					 */
					gridd_get_template_part( 'grid-parts/header/template-social-media' );

				} else {
					/**
					 * Fallback.
					 */
					do_action( 'gridd_the_grid_part', $part );
				}
			}
		}
		?>
	</div>
</div>

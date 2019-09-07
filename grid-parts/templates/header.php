<?php
/**
 * Template part for the Header.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
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
			'small'      => Grid::get_options( 'gridd_header_grid' ),
			'large'      => Grid::get_options( 'gridd_header_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '992px' ),
			'selector'   => '.gridd-tp-header > .inner',
			'prefix'     => true,
		]
	)
);
$style->add_file( get_theme_file_path( 'grid-parts/styles/header/styles.min.css' ) );
$style->add_string( '@media only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){' );
$style->add_file( get_theme_file_path( 'grid-parts/styles/header-large.min.css' ) );

// If we're on an archive and we want to use cards, add extra styles.
if ( ( is_archive() || is_home() ) && 'card' === get_theme_mod( 'gridd_archive_post_mode', 'default' ) ) {
	$style->add_file( get_theme_file_path( 'assets/css/core/archive-cards.min.css' ) );
}

$style->add_string( '}' );
if ( true === get_theme_mod( 'gridd_header_sticky', false ) && false === get_theme_mod( 'gridd_header_sticky_mobile', false ) ) {
	$style->add_string( '@media only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){.gridd-tp.gridd-tp-header.gridd-sticky{position:relative;}}' );
}

// Get the header image.
$styles        = '';
$header_bg_img = get_header_image();
if ( $header_bg_img ) {
	// If we have a header image, add it as a background.
	$style->add_string( '.gridd-tp.gridd-tp-header{background-image:url(\'' . esc_url_raw( $header_bg_img ) . '\');background-size:cover;background-position:center center;}' );
}

// Force-override parts background images.
if ( get_theme_mod( 'gridd_grid_part_details_header_parts_background_override', false ) ) {
	$style->add_string( '.gridd-tp.gridd-tp-header .gridd-tp,.gridd-tp.gridd-tp-header .gridd-tp inner{background:none !important;}' );
}

// Add css-vars to be replaced.
$style->add_vars(
	[
		'--h-bg' => get_theme_mod( 'gridd_grid_part_details_header_background_color', '#ffffff' ),
		'--h-bs' => get_theme_mod( 'gridd_grid_header_box_shadow', '0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)' ),
		'--h-mw' => get_theme_mod( 'gridd_grid_header_max_width', '45em' ),
		'--h-gg' => get_theme_mod( 'gridd_grid_header_grid_gap', '0' ),
	]
);

$wrapper_class  = 'gridd-tp gridd-tp-header';
$wrapper_class .= get_theme_mod( 'gridd_header_sticky', false ) ? ' gridd-sticky' : '';
$attrs          = [
	'class' => $wrapper_class,
	'role'  => 'banner',
];
?>

<div <?php Theme::print_attributes( $attrs, 'wrapper-header' ); ?>>
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
				if ( 'header_branding' === $part && apply_filters( 'gridd_render_grid_part', true, 'header_branding' ) ) {
					/**
					 * Branding.
					 */
					Theme::get_template_part( 'grid-parts/templates/header-branding' );

				} elseif ( 'header_search' === $part && apply_filters( 'gridd_render_grid_part', true, 'header_search' ) ) {
					/**
					 * Search.
					 */
					Theme::get_template_part( 'grid-parts/templates/header-search' );

				} elseif ( 'header_contact_info' === $part && apply_filters( 'gridd_render_grid_part', true, 'header_contact_info' ) ) {
					/**
					 * Contact Info.
					 */
					Theme::get_template_part( 'grid-parts/templates/header-contact-info' );

				} elseif ( 'social_media' === $part && apply_filters( 'gridd_render_grid_part', true, 'social_media' ) ) {
					/**
					 * Social Media.
					 */
					Theme::get_template_part( 'grid-parts/templates/header-social-media' );

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

<?php
/**
 * Template part for the footer.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Grid;
use Gridd\Grid_Part\Footer;
use Gridd\Style;

// Get the grid settings.
$settings = Grid::get_options( 'footer_grid', Footer::get_grid_defaults() );

\Gridd\CSS::add_string(
	Grid::get_styles_responsive(
		[
			'context'    => 'footer',
			'large'      => Grid::get_options( 'footer_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '992px' ),
			'selector'   => '.gridd-tp-footer > .inner',
			'prefix'     => true,
		]
	)
);

// Add the stylesheet.
\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Footer/styles.min.css' ) );

$attrs = [
	'class' => 'gridd-tp gridd-tp-footer',
	'role'  => 'contentinfo',
];

if ( get_theme_mod( 'footer_custom_options', false ) ) {
	$attrs['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attrs, 'wrapper-footer' ); ?>>
	<div class="inner">
		<?php
		if ( isset( $settings['areas'] ) ) {
			foreach ( array_keys( $settings['areas'] ) as $part ) {

				if ( 0 === strpos( $part, 'footer_sidebar_' ) ) {
					$sidebar_id = (int) str_replace( 'footer_sidebar_', '', $part );
					if ( apply_filters( 'gridd_render_grid_part', true, 'footer_sidebar_' . $sidebar_id ) ) {
						/**
						 * Footer Sidebars.
						 * We use include( get_theme_file_path() ) here
						 * because we need to pass the $sidebar_id var to the template.
						 */
						include get_theme_file_path( 'grid-parts/Footer/template-sidebar.php' );
					}
				} elseif ( 'footer_social_media' === $part && apply_filters( 'gridd_render_grid_part', true, 'footer_social_media' ) ) {
					/**
					 * Social Media.
					 */
					Theme::get_template_part( 'grid-parts/Footer/template-social-media' );

				} elseif ( 'footer_copyright' === $part && apply_filters( 'gridd_render_grid_part', true, 'footer_copyright' ) ) {
					/**
					 * Copyright.
					 */
					Theme::get_template_part( 'grid-parts/Footer/template-copyright' );

				} else {
					do_action( 'gridd_the_grid_part', $part );
				}
			}
		}
		?>
	</div>
</div>

<?php
/**
 * Template part for the footer.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid;
use Gridd\Grid_Part\Footer;
use Gridd\Style;

// Get the grid settings.
$settings = Grid::get_options( 'gridd_footer_grid', Footer::get_grid_defaults() );

$style = Style::get_instance( 'grid-part/footer' );

// Add the main grid styles.
$style->add_string(
	Grid::get_styles_responsive(
		[
			'context'    => 'footer',
			'large'      => Grid::get_options( 'gridd_footer_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '800px' ),
			'selector'   => '.gridd-tp-footer > .inner',
			'prefix'     => true,
		]
	)
);

// Add the stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/footer/styles/default.min.css' ) );

// Add css-vars.
$style->add_vars(
	[
		'--gridd-footer-bg'               => get_theme_mod( 'gridd_grid_footer_background_color', '#ffffff' ),
		'--gridd-footer-border-top-width' => get_theme_mod( 'gridd_grid_footer_border_top_width', 1 ) . 'px',
		'--gridd-footer-border-top-color' => get_theme_mod( 'gridd_grid_footer_border_top_color', 'rgba(0,0,0,.1)' ),
		'--gridd-footer-max-width'        => get_theme_mod( 'gridd_grid_footer_max_width', '' ),
	]
);
?>

<div class="gridd-tp gridd-tp-footer">
	<?php
	/**
	 * Print the styles.
	 */
	$style->the_css( 'gridd-inline-css-footer' );
	?>
	<div class="inner">
		<?php
		if ( isset( $settings['areas'] ) ) {
			foreach ( array_keys( $settings['areas'] ) as $part ) {

				if ( 0 === strpos( $part, 'footer_sidebar_' ) ) {
					/**
					 * Footer Sidebars.
					 * We use include( get_theme_file_path() ) here
					 * because we need to pass the $sidebar_id var to the template.
					 */
					$sidebar_id = (int) str_replace( 'footer_sidebar_', '', $part );
					include get_theme_file_path( 'grid-parts/footer/template-footer-sidebar.php' );

				} elseif ( 'footer_social_media' === $part ) {
					/**
					 * Social Media.
					 */
					get_template_part( 'grid-parts/footer/template-social-media' );

				} elseif ( 'footer_copyright' === $part ) {
					/**
					 * Copyright.
					 */
					get_template_part( 'grid-parts/footer/template-footer-copyright' );

				} else {
					do_action( 'gridd_the_grid_part', $part );
				}
			}
		}
		?>
	</div>
</div>

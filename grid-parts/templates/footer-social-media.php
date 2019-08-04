<?php
/**
 * Template part for the Footer Social-Media.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Footer;
use Gridd\Style;
use Gridd\Theme;

$setting = get_theme_mod( 'gridd_grid_part_details_footer_social_icons', [] );
if ( ! function_exists( 'gridd_social_icons_svg' ) ) {

	// Include Social Icons Definitions.
	require_once get_template_directory() . '/inc/social-icons.php';
}
$icons = gridd_social_icons_svg();

// Init Style class.
$style = Style::get_instance( 'grid-part/footer/social-media' );

// Add css-vars to be replaced.
$style->add_vars(
	[
		'--gridd-footer-social-icons-bg'         => get_theme_mod( 'gridd_grid_part_details_footer_social_icons_background_color', '#fff' ),
		'--gridd-footer-social-icons-text-align' => get_theme_mod( 'gridd_grid_part_details_footer_social_icons_icons_text_align', 'flex-end' ),
		'--gridd-footer-social-icons-size'       => get_theme_mod( 'gridd_grid_part_details_footer_social_icons_size', '1' ),
		'--gridd-footer-social-icons-padding'    => get_theme_mod( 'gridd_grid_part_details_footer_social_icons_padding', '0.5' ),
		'--gridd-footer-social-icons-color'      => get_theme_mod( 'gridd_grid_part_details_footer_social_icons_icons_color', '#000' ),
	]
);

// Add stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/styles/footer/styles-social-icons.min.css' ) );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-footer_social_media' ], 'wrapper-footer_social_media' ); ?>>
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-footer-social-icons' );

	foreach ( $setting as $icon ) {
		if ( ! empty( $icon['url'] ) && ! empty( $icon['icon'] ) && isset( $icons[ $icon['icon'] ] ) ) {
			$url = ( 'mail' === $icon['icon'] ) ? 'mailto:' . antispambot( $icon['url'] ) : $icon['url'];

			echo '<a href="' . esc_url_raw( $url ) . '" target="_blank" rel="noopener" title="' . esc_attr( $icon['icon'] ) . '">';

			/**
			 * Note to code reviewers:
			 * The icons here are hardcoded and there's no user input.
			 * There's no need to escape them.
			 */
			echo $icons[ $icon['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput

			// Close the link.
			echo '</a>';
		}
	}
	?>
</div>

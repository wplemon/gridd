<?php
/**
 * Template part for the Header Social-Media.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Header;
use Gridd\Style;

$setting = get_theme_mod( 'gridd_grid_part_details_social_icons', [] );
if ( ! function_exists( 'gridd_social_icons_svg' ) ) {

	// Include Social Icons Definitions.
	require_once get_template_directory() . '/inc/social-icons.php';
}
$icons = gridd_social_icons_svg();

// Init Style class.
$style = Style::get_instance( 'grid-part/header/social-media' );

// Add CSS-vars.
$style->add_vars(
	[
		'--gridd-header-social-icons-bg'         => get_theme_mod( 'gridd_grid_part_details_social_icons_background_color', '#ffffff' ),
		'--gridd-header-social-icons-text-align' => get_theme_mod( 'gridd_grid_part_details_social_icons_icons_text_align', 'right' ),
		'--gridd-header-social-icons-size'       => get_theme_mod( 'gridd_grid_part_details_social_icons_size', .85 ) . 'em',
		'--gridd-header-social-icons-padding'    => get_theme_mod( 'gridd_grid_part_details_social_icons_padding', .5 ) . 'em',
		'--gridd-header-social-icons-color'      => get_theme_mod( 'gridd_grid_part_details_social_icons_icons_color', '#000000' ),
	]
);

// Add stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/header/styles/social-icons.min.css' ) );
?>

<div class="gridd-tp gridd-tp-social_media">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-header-social-media' );

	foreach ( $setting as $icon ) {
		if ( ! empty( $icon['url'] ) && ! empty( $icon['icon'] ) && isset( $icons[ $icon['icon'] ] ) ) {
			$url = ( 'mail' === $icon['icon'] ) ? 'mailto:' . antispambot( $icon['url'] ) : $icon['url'];
			echo '<a href="' . esc_url_raw( $url ) . '" target="_blank" rel="noopener" title="' . esc_attr( $icon['icon'] ) . '">';

			/**
			 * Note to code reviewers:
			 * The icons here are hardcoded and there's no user-input.
			 * There's no need to escape them.
			 */
			echo $icons[ $icon['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			echo '</a>';
		}
	}
	?>
</div>

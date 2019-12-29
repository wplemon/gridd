<?php
/**
 * Template part for the Footer Social-Media.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

$setting = get_theme_mod( 'footer_social_icons', [] );
if ( ! function_exists( 'gridd_social_icons_svg' ) ) {

	// Include Social Icons Definitions.
	require_once get_template_directory() . '/inc/social-icons.php';
}
$icons = gridd_social_icons_svg();

// Add styles.
Style::get_instance( 'grid-part/footer/social-media' )
	->add_file( get_theme_file_path( 'grid-parts/Footer/styles-social-icons.min.css' ) )
	->the_css( 'gridd-inline-css-footer-social-icons' );

$attributes = [
	'class' => 'gridd-tp gridd-tp-footer_social_media',
];
if ( get_theme_mod( 'footer_social_media_custom_options', false ) ) {
	$attributes['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attributes, 'wrapper-footer_social_media' ); ?>>
	<?php

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

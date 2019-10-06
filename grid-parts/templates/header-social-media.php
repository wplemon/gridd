<?php
/**
 * Template part for the Header Social-Media.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Header;
use Gridd\Style;
use Gridd\Theme;

$setting = get_theme_mod( 'header_social_icons', [] );
if ( ! function_exists( 'gridd_social_icons_svg' ) ) {

	// Include Social Icons Definitions.
	require_once get_template_directory() . '/inc/social-icons.php';
}
$icons = gridd_social_icons_svg();

// Init Style class.
$style = Style::get_instance( 'grid-part/header/social-media' );

// Add stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/styles/header/styles-social-icons.min.css' ) );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-social_media' ], 'wrapper-social_media' ); ?>>
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
			echo $icons[ $icon['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput

			echo '</a>';
		}
	}
	?>
</div>

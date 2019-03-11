<?php
/**
 * Template part for the Header Branding.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;

$style = Style::get_instance( 'grid-part/header/branding' );
$style->add_vars(
	[
		'--gridd-branding-bg'               => get_theme_mod( 'gridd_grid_header_branding_background_color', '#ffffff' ),
		'--gridd-branding-horizontal-align' => get_theme_mod( 'gridd_grid_header_branding_horizontal_align', 'left' ),
		'--gridd-branding-vertical-align'   => get_theme_mod( 'gridd_grid_header_branding_vertical_align', 'center' ),
		'--gridd-branding-padding'          => get_theme_mod( 'gridd_grid_header_branding_padding', '0.5em' ),
		'--gridd-branding-elements-padding' => get_theme_mod( 'gridd_branding_inline_spacing', 1 ) . 'em',
		'--gridd-logo-max-width'            => get_theme_mod( 'gridd_logo_max_width', 100 ) . 'px',
		'--gridd-sitetitle-size'            => get_theme_mod( 'gridd_branding_sitetitle_size', 2 ) . 'em',
		'--gridd-tagline-size'              => get_theme_mod( 'gridd_branding_tagline_size', 1 ) . 'em',
		'--header-textcolor'                => get_header_textcolor(),
	]
);
$style->add_string( ':root{--header-textcolor:#' . esc_attr( trim( get_header_textcolor(), '#' ) ) . ';}' );
$style->add_file( get_theme_file_path( 'grid-parts/styles/header/styles-branding.min.css' ) );
$style->the_css( 'gridd-inline-css-header-branding' );
?>
<div class="gridd-tp gridd-tp-header_branding<?php echo ( has_custom_logo() ) ? ' has-logo' : ''; ?><?php echo get_theme_mod( 'gridd_branding_inline', false ) ? ' inline' : ' vertical'; ?>">
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
	<?php endif; ?>

	<?php if ( is_front_page() && is_home() ) : ?>
		<h1 class="site-title h3<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</h1>
	<?php else : ?>
		<p class="site-title h3<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</p>
	<?php endif; ?>
	<?php $description = get_bloginfo( 'description', 'display' ); ?>
	<?php if ( $description || is_customize_preview() ) : ?>
		<p class="site-description<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>"><?php echo $description; /* WPCS: xss ok. */ ?></p>
	<?php endif; ?>
</div>

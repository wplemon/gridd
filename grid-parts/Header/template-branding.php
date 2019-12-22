<?php
/**
 * Template part for the Header Branding.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

$style = Style::get_instance( 'grid-part/header/branding' );
$style->add_string( ':root{--header-textcolor:#' . esc_attr( trim( get_header_textcolor(), '#' ) ) . ';}' );
$style->add_file( get_theme_file_path( 'grid-parts/Header/styles-branding.min.css' ) );
$style->the_css( 'gridd-inline-css-header-branding' );

$wrapper_class  = 'gridd-tp gridd-tp-header_branding';
$wrapper_class .= get_theme_mod( 'gridd_branding_inline', false ) ? ' inline' : ' vertical';
$wrapper_class .= get_theme_mod( 'branding_custom_options', false ) ? ' custom-options' : '';
$wrapper_class .= ( has_custom_logo() ) ? ' has-logo' : '';
?>

<div <?php Theme::print_attributes( [ 'class' => $wrapper_class ], 'wrapper-header_branding' ); ?>>
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
		<p class="site-description<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>
</div>

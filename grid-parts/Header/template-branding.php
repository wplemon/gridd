<?php
/**
 * Template part for the Header Branding.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

\Gridd\CSS::add_string( ':root{--header-textcolor:#' . esc_attr( trim( get_header_textcolor(), '#' ) ) . ';}' );
\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Header/styles-branding.min.css' ) );

$wrapper_class  = 'gridd-tp gridd-tp-header_branding';
$wrapper_class .= get_theme_mod( 'gridd_branding_inline', false ) ? ' inline' : ' vertical';
$wrapper_class .= get_theme_mod( 'branding_custom_options', false ) ? ' custom-options' : '';
$wrapper_class .= ( has_custom_logo() ) ? ' has-logo' : '';
?>

<div <?php Theme::print_attributes( [ 'class' => $wrapper_class ], 'wrapper-header_branding' ); ?>>
	<?php if ( has_custom_logo() ) : ?>
		<?php the_custom_logo(); ?>
	<?php endif; ?>

	<h1 class="site-title h3<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>
	</h1>
	<?php $description = get_bloginfo( 'description', 'display' ); ?>
	<?php if ( $description || is_customize_preview() ) : ?>
		<p class="site-description<?php echo ( ! display_header_text() ) ? ' hidden' : ''; ?>"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>
</div>

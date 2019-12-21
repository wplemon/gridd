<?php
/**
 * Template part for the footer copyright.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

// Add styles.
Style::get_instance( 'grid-part/footer/copyright' )
	->add_file( get_theme_file_path( 'grid-parts/Footer/styles-copyright.min.css' ) )
	->the_css( 'gridd-inline-css-footer-copyright' );

$attributes = [
	'class' => 'gridd-tp gridd-tp-footer_copyright',
];
if ( get_theme_mod( 'footer_copyright_custom_options', false ) ) {
	$attributes['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attributes, 'wrapper-footer_copyright' ); ?>>
	<div class="site-info">
		<div class="site-info-text">
			<?php
			echo wp_kses_post(
				get_theme_mod(
					'gridd_copyright_text',
					sprintf(
						/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
						__( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ),
						'<a href="https://wordpress.org/">WordPress</a>',
						'Gridd',
						'<a href="https://wplemon.com/">wplemon.com</a>'
					)
				)
			);
			?>
		</div>
	</div>
</div>

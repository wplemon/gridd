<?php
/**
 * Template part for the footer copyright.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
// Add styles.
$styles = Style::get_instance( 'grid-part/footer/copyright' );
$styles->add_vars(
	[
		'--gridd-footer-padding'               => get_theme_mod( 'gridd_grid_footer_padding', '1em' ),
		'--gridd-footer-copyright-bg'          => get_theme_mod( 'gridd_grid_footer_copyright_bg_color', '#ffffff' ),
		'--gridd-footer-copyright-color'       => get_theme_mod( 'gridd_grid_footer_copyright_color', '#000000' ),
		'--gridd-footer-copyright-font-size'   => get_theme_mod( 'gridd_grid_footer_copyright_text_font_size', 1 ) . 'em',
		'--gridd-footer-copyright-text-align'  => get_theme_mod( 'gridd_grid_footer_copyright_text_align', 'left' ),
		'--gridd-footer-copyright-links-color' => get_theme_mod( 'gridd_grid_footer_copyright_links_color', '#0f5e97' ),
	]
);
$styles->add_file( get_theme_file_path( 'grid-parts/styles/footer/copyright.min.css' ) );
$styles->the_css( 'gridd-inline-css-footer-copyright' );

?>

<div class="gridd-tp gridd-tp-footer_copyright">
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

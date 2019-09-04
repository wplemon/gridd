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
	->add_file( get_theme_file_path( 'grid-parts/styles/footer/styles-copyright.min.css' ) )
	->add_vars(
		[
			'--ft-pd'    => get_theme_mod( 'gridd_grid_footer_padding', '1em' ),
			'--ft-cp-bg' => get_theme_mod( 'gridd_grid_footer_copyright_bg_color', '#fff' ),
			'--ft-cp-cl' => get_theme_mod( 'gridd_grid_footer_copyright_color', '#000' ),
			'--ft-cp-fs' => get_theme_mod( 'gridd_grid_footer_copyright_text_font_size', '1em' ),
			'--ft-cp-ta' => get_theme_mod( 'gridd_grid_footer_copyright_text_align', 'center' ),
			'--ft-cp-lc' => get_theme_mod( 'gridd_grid_footer_copyright_links_color', '#0f5e97' ),
		]
	)
	->the_css( 'gridd-inline-css-footer-copyright' );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-footer_copyright' ], 'wrapper-footer_copyright' ); ?>>
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

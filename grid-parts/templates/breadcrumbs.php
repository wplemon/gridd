<?php
/**
 * Template part for the breadcrumbs.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

// Early exit if we're on the frontpage.
if ( is_front_page() || is_home() ) {
	return;
}
?>
<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-breadcrumbs' ], 'wrapper-breadcrumbs' ); ?>>
	<?php
	/**
	 * Print styles.
	 */
	Style::get_instance( 'grid-part/breadcrumbs' )
		->add_file( get_theme_file_path( 'grid-parts/styles/breadcrumbs/styles.min.css' ) )
		->add_vars(
			[
				'--brd-bg' => get_theme_mod( 'gridd_grid_breadcrumbs_background_color', '#fff' ),
				'--brd-fs' => get_theme_mod( 'gridd_grid_breadcrumbs_font_size', '1' ),
				'--brd-cl' => get_theme_mod( 'gridd_grid_breadcrumbs_color', '#000' ),
				'--brd-pd' => get_theme_mod( 'gridd_grid_breadcrumbs_padding', '1em' ),
				'--brd-mw' => get_theme_mod( 'gridd_grid_breadcrumbs_max_width', '100%' ),
				'--brd-ta' => get_theme_mod( 'gridd_grid_breadcrumbs_text_align', 'center' ),
			]
		)
		->the_css( 'gridd-inline-css-breadcrumbs' );
	?>
	<div class="inner">
		<?php
		// The breadcrumbs.
		\Hybrid\Breadcrumbs\Trail::display(
			apply_filters(
				'gridd_breadcrumbs_args',
				[
					'show_on_front' => false,
					'labels'        => [
						'title' => false,
					],
				]
			)
		);
		?>
	</div>
</div>

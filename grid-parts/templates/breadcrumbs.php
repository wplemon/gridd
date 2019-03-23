<?php
/**
 * Template part for the breadcrumbs.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;

// Early exit if we're on the frontpage.
if ( is_front_page() || is_home() ) {
	return;
}

// Add styles.
$style = Style::get_instance( 'grid-part/breadcrumbs' );
$style->add_file( get_theme_file_path( 'grid-parts/styles/breadcrumbs/styles.min.css' ) );
$style->add_vars(
	[
		'--gridd-breadcrumbs-bg'         => get_theme_mod( 'gridd_grid_breadcrumbs_background_color', '#ffffff' ),
		'--gridd-breadcrumbs-font-size'  => get_theme_mod( 'gridd_grid_breadcrumbs_font_size', 1 ) . 'em',
		'--gridd-breadcrumbs-color'      => get_theme_mod( 'gridd_grid_breadcrumbs_color', '#000000' ),
		'--gridd-breadcrumbs-padding'    => get_theme_mod( 'gridd_grid_breadcrumbs_padding', '1em' ),
		'--gridd-breadcrumbs-text-align' => get_theme_mod( 'gridd_grid_breadcrumbs_text_align', 'center' ),
		'--gridd-breadcrumbs-max-width'  => get_theme_mod( 'gridd_grid_breadcrumbs_max_width', '100%' ),
	]
);

$wrapper_class = apply_filters( 'gridd_grid_part_class', 'gridd-tp gridd-tp-breadcrumbs', 'breadcrumbs' );
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-breadcrumbs' );
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

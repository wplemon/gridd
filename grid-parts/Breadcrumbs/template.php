<?php
/**
 * Template part for the breadcrumbs.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

// Early exit if we're on the frontpage.
if ( is_front_page() || is_home() ) {
	return;
}

$grid_part_class = 'gridd-tp gridd-tp-breadcrumbs';
if ( get_theme_mod( 'breadcrumbs_custom_options', false ) ) {
	$grid_part_class .= ' custom-options';
}
?>
<div <?php Theme::print_attributes( [ 'class' => $grid_part_class ], 'wrapper-breadcrumbs' ); ?>>
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

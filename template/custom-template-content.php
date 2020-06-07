<?php
/**
 * Template Name: Content Only
 *
 * Hides all grid-parts and only renders the content and the mobile-nav.
 *
 * @package Gridd
 */

use Gridd\Theme;

/**
 * Modify the grid.
 */
add_filter(
	'gridd_get_options',
	/**
	 * Filters the value of the grid.
	 *
	 * @since 1.0.3
	 * @param mixed  $value     The theme-mod value.
	 * @param string $theme_mod The theme-mod.
	 * @return mixed
	 */
	function( $value, $theme_mod ) {
		if ( 'gridd_grid' === $theme_mod ) {
			return [
				'rows'         => 1,
				'columns'      => 1,
				'areas'        => [
					'content' => [
						'cells' => [ [ 1, 1 ] ],
					],
				],
				'gridTemplate' => [
					'rows'    => [ 'auto' ],
					'columns' => [ 'auto' ],
				],
			];
		}
		return $value;
	},
	10,
	2
);
get_header();

while ( have_posts() ) {
	the_post();

	Theme::get_template_part( 'template-parts/content', 'page' );
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_sidebar();
get_footer();

<?php
/**
 * Template part for the Header.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Grid;
use Gridd\Grid_Part\Header;
use Gridd\Style;

// Get the grid settings.
$settings = Grid::get_options( 'header_grid', Header::get_grid_defaults() );

// Add styles.
\Gridd\CSS::add_string(
	Grid::get_styles_responsive(
		[
			'context'    => 'header',
			'small'      => Grid::get_options( 'header_grid' ),
			'large'      => Grid::get_options( 'header_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '992px' ),
			'selector'   => '.gridd-tp-header > .inner',
			'prefix'     => true,
		]
	)
);
\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Header/styles.min.css' ) );
\Gridd\CSS::add_file(
	get_theme_file_path( 'grid-parts/Header/styles-large.min.css' ),
	'only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . ')'
);

// If we're on an archive and we want to use cards, add extra styles.
if ( ( is_archive() || is_home() ) && 'card' === get_theme_mod( 'archive_post_mode', 'default' ) ) {
	\Gridd\CSS::add_file(
		get_theme_file_path( 'assets/css/core/archive-cards.min.css' ),
		'only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . ')'
	);
}

if ( true === get_theme_mod( 'gridd_header_sticky', false ) ) {
	\Gridd\CSS::add_string(
		'.gridd-tp.gridd-tp-header.gridd-sticky{position:relative;}.admin-bar .gridd-tp.gridd-sticky{--adminbar-height:0;}',
		'only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . ')'
	);
}

$wrapper_class  = 'gridd-tp gridd-tp-header';
$wrapper_class .= get_theme_mod( 'gridd_header_sticky', false ) ? ' gridd-sticky' : '';
$wrapper_class .= get_theme_mod( 'header_custom_options', false ) ? ' custom-options' : '';
$attrs          = [
	'class' => $wrapper_class,
	'role'  => 'banner',
];
?>

<div <?php Theme::print_attributes( $attrs, 'wrapper-header' ); ?>>
	<div class="inner">
		<?php
		if ( isset( $settings['areas'] ) ) {
			foreach ( array_keys( $settings['areas'] ) as $part ) {
				if ( 'header_branding' === $part && apply_filters( 'gridd_render_grid_part', true, 'header_branding' ) ) {
					/**
					 * Branding.
					 */
					Theme::get_template_part( 'grid-parts/Header/template-branding' );

				} else {
					/**
					 * Fallback.
					 */
					do_action( 'gridd_the_grid_part', $part );
				}
			}
		}
		?>
	</div>
</div>

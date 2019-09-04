<?php
/**
 * Template part for the Sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Style;
use Gridd\Theme;

$style = Style::get_instance( "grid-part/sidebar/$sidebar_id" );
if ( ! Sidebar::$global_styles_added ) {
	$style->add_file( get_theme_file_path( 'grid-parts/styles/sidebar/global.min.css' ) );
}
$style->add_file( get_theme_file_path( 'grid-parts/styles/sidebar/styles.min.css' ) );
$style->replace( 'ID', absint( $sidebar_id ) );

$style->add_vars(
	[
		"--wa-{$sidebar_id}-bg" => get_theme_mod( "gridd_grid_sidebar_{$sidebar_id}_background_color", '#ffffff' ),
		"--wa-{$sidebar_id}-lc" => get_theme_mod( "gridd_grid_sidebar_{$sidebar_id}_links_color", '#0f5e97' ),
		"--wa-{$sidebar_id}-cl" => get_theme_mod( "gridd_grid_sidebar_{$sidebar_id}_color", '#000000' ),
	]
);
$attrs = [
	'class' => 'gridd-tp gridd-tp-sidebar gridd-tp-sidebar_' . absint( $sidebar_id ),
	'role'  => 'complementary',
]
?>
<div <?php Theme::print_attributes( $attrs, 'wrapper-sidebar_' . absint( $sidebar_id ) ); ?>>
	<?php $style->the_css( 'gridd-inline-css-sidebar-' . $sidebar_id ); ?>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

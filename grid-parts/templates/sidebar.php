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


?>
<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-sidebar gridd-tp-sidebar_' . absint( $sidebar_id ), 'role' => 'complementary' ], 'wrapper-sidebar_' . absint( $sidebar_id ) ); ?>>
	<?php $style->the_css( 'gridd-inline-css-sidebar-' . $sidebar_id ); ?>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

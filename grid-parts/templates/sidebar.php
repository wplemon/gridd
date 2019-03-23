<?php
/**
 * Template part for the Sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Style;

$wrapper_class = 'gridd-tp gridd-tp-sidebar gridd-tp-sidebar_' . absint( $sidebar_id );
$wrapper_class = apply_filters( 'gridd_grid_part_class', $wrapper_class, 'sidebar-' . absint( $sidebar_id ) );

$style = Style::get_instance( "grid-part/sidebar/$id" );
if ( ! Sidebar::$global_styles_added ) {
	$style->add_file( get_theme_file_path( 'grid-parts/styles/sidebar/global.min.css' ) );
}
$style->add_file( get_theme_file_path( 'grid-parts/styles/sidebar/styles.min.css' ) );
$style->replace( 'ID', absint( $sidebar_id ) );


?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php $style->the_css( 'gridd-inline-css-sidebar-' . $sidebar_id ); ?>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

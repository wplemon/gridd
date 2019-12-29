<?php
/**
 * Template part for the Sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Theme;

$attrs = [
	'class' => 'gridd-tp gridd-tp-sidebar gridd-tp-sidebar_' . absint( $sidebar_id ),
	'role'  => 'complementary',
];

if ( get_theme_mod( "sidebar_{$sidebar_id}_custom_options", false ) ) {
	$attrs['class'] .= ' custom-options';
}
?>
<div <?php Theme::print_attributes( $attrs, 'wrapper-sidebar_' . absint( $sidebar_id ) ); ?>>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

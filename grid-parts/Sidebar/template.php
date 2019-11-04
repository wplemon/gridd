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
]
?>
<div <?php Theme::print_attributes( $attrs, 'wrapper-sidebar_' . absint( $sidebar_id ) ); ?>>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

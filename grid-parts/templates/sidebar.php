<?php
/**
 * Template part for the Sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

$wrapper_class = 'gridd-tp gridd-tp-sidebar gridd-tp-sidebar_' . absint( $sidebar_id );
$wrapper_class = apply_filters( 'gridd_grid_part_class', $wrapper_class, 'sidebar-' . absint( $sidebar_id ) );
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<style>.gridd-tp-sidebar_<?php echo absint( $sidebar_id ); ?>{flex-direction:column;justify-content:flex-start;}</style>
	<?php dynamic_sidebar( "sidebar-$sidebar_id" ); ?>
</div>

<?php
/**
 * Template part for the Sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

?>
<div class="gridd-tp gridd-tp-sidebar_<?php echo absint( $id ); ?>">
	<style>.gridd-tp-sidebar_<?php echo absint( $id ); ?>{flex-direction:column;justify-content:flex-start;}</style>
	<?php dynamic_sidebar( "sidebar-$id" ); ?>
</div>

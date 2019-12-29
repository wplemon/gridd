<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

?>
<div id="gridd-handheld-home">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gridd-nav-handheld-btn home" title="<?php esc_attr_e( 'Home', 'gridd' ); ?>">
		<svg aria-hidden="true" class="griddinline-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 6.453l9 8.375v9.172h-6v-6h-6v6h-6v-9.172l9-8.375zm12 5.695l-12-11.148-12 11.133 1.361 1.465 10.639-9.868 10.639 9.883 1.361-1.465z"/></svg>
		<span class="<?php echo get_theme_mod( 'nav-handheld_hide_labels', false ) ? 'screen-reader-text' : 'label'; ?>">
			<?php esc_html_e( 'Home', 'gridd' ); ?>
		</span>
	</a>
</div>

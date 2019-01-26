<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

$label_class = get_theme_mod( 'gridd_grid_nav-handheld_hide_labels', false ) ? 'screen-reader-text' : 'label';
?>
<div id="gridd-handheld-widget-area">
	<?php
	/**
	 * Prints the button.
	 * No need to escape this, it's already escaped in the function itself.
	 */
	echo gridd_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		[
			'expanded_state_id' => 'griddHandheldWidgetAreaWrapper',
			'expanded'          => 'false',
			'label'             => get_theme_mod( 'gridd_grid_nav-handheld_widget_area_icon', '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z"/></svg>' ) . '<span class="' . $label_class . '">' . get_theme_mod( 'gridd_grid_nav-handheld_widget_area_label', esc_html__( 'Settings', 'gridd' ) ) . '</span>',
			'classes'           => [ 'gridd-nav-handheld-btn' ],
		]
	);
	?>
	<div id="gridd-handheld-widget-area-wrapper" class="gridd-hanheld-nav-popup-wrapper">
		<?php dynamic_sidebar( 'sidebar_handheld_widget_area' ); ?>
		<?php if ( ! is_active_sidebar( 'sidebar_handheld_widget_area' ) ) : ?>
			<div style="padding: 2em;"><?php esc_html_e( 'Please add a widget to this widget area to see your content.', 'gridd' ); ?></div>
		<?php endif; ?>
	</div>
</div>

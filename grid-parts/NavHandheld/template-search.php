<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

$label_class = get_theme_mod( 'nav-handheld_hide_labels', false ) ? 'screen-reader-text' : 'label';
?>
<div id="gridd-handheld-search">
	<?php
	/**
	 * Prints the button.
	 * No need to escape this, there's zero user input.
	 * Everything is hardcoded and things that need escaping
	 * are properly escaped in the function itself.
	 */
	echo Theme::get_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput
		[
			'context'           => [ 'handheld-search' ],
			'expanded_state_id' => 'griddHandheldSearchWrapper',
			'expanded'          => 'false',
			'label'             => '<svg aria-hidden="true" class="gridd-inline-icon" class="gridd-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.809 21.646l-6.205-6.205c1.167-1.605 1.857-3.579 1.857-5.711 0-5.365-4.365-9.73-9.731-9.73-5.365 0-9.73 4.365-9.73 9.73 0 5.366 4.365 9.73 9.73 9.73 2.034 0 3.923-.627 5.487-1.698l6.238 6.238 2.354-2.354zm-20.955-11.916c0-3.792 3.085-6.877 6.877-6.877s6.877 3.085 6.877 6.877-3.085 6.877-6.877 6.877c-3.793 0-6.877-3.085-6.877-6.877z"/></svg><span class="' . $label_class . '">' . esc_html__( 'Search', 'gridd' ) . '</span>',
			'classes'           => [ 'gridd-nav-handheld-btn' ],
		]
	);
	?>
	<div id="gridd-handheld-search-wrapper" class="gridd-hanheld-nav-popup-wrapper gridd-nav-handheld-form">
		<?php get_search_form( true ); ?>
	</div>
</div>

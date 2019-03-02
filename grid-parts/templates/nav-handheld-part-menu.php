<?php
/**
 * Template part for the handheld navigation.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Grid_Part\Navigation;

$style = Style::get_instance( 'grid-part/nav-handheld/menu' );
$style->add_string( Navigation::get_global_styles() );
$style->add_file( get_theme_file_path( 'grid-parts/styles/nav-handheld/navigation.min.css' ) );
$style->add_vars(
	[
		'--gridd-text-color'  => get_theme_mod( 'gridd_text_color', '#000000' ),
		'--gridd-links-color' => get_theme_mod( 'gridd_links_color', '#0f5e97' ),
		'--gridd-content-bg'  => get_theme_mod( 'gridd_grid_content_background_color', '#ffffff' ),
	]
);
$style->the_css( 'gridd-inline-css-nav-handheld-menu' );

$label_class = get_theme_mod( 'gridd_grid_nav-handheld_hide_labels', false ) ? 'screen-reader-text' : 'label';
?>
<nav id="gridd-handheld-nav" class="gridd-nav-vertical">
	<?php
	/**
	 * Prints the button.
	 * No need to escape this, it's already escaped in the function itself.
	 */
	echo gridd_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		[
			'expanded_state_id' => 'griddHandheldNavMenu',
			'expanded'          => 'false',
			'label'             => '<svg class="gridd-inline-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 6h-24v-4h24v4zm0 4h-24v4h24v-4zm0 8h-24v4h24v-4z"/></svg><span class="' . $label_class . '">' . esc_html__( 'Menu', 'gridd' ) . '</span>',
			'classes'           => [ 'gridd-nav-handheld-btn' ],
		]
	);
	?>
	<div id="gridd-handheld-menu-wrapper" class="gridd-hanheld-nav-popup-wrapper">
		<?php
		wp_nav_menu(
			[
				'theme_location' => 'menu-handheld',
				'menu_id'        => 'nav-handheld',
				'item_spacing'   => 'discard',
				'container'      => false,
			]
		);
		?>
	</div>
</nav>

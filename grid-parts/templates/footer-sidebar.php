<?php
/**
 * Template part for the footer sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;

$style = Style::get_instance( "grid-part/footer/sidebar/$sidebar_id" );

// Add stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/styles/footer/sidebar.min.css' ) );

// Replace "ID" in the stylesheet.
$style->replace( 'ID', $sidebar_id );

// Add CSS-vars to replace.
$style->add_vars(
	[
		"--gridd-footer-sidebar-$sidebar_id-bg"          => get_theme_mod( "gridd_grid_footer_sidebar_{$sidebar_id}_bg_color", '#ffffff' ),
		"--gridd-footer-sidebar-$sidebar_id-color"       => get_theme_mod( "gridd_grid_footer_sidebar_{$sidebar_id}_color", '#000000' ),
		"--gridd-footer-sidebar-$sidebar_id-links-color" => get_theme_mod( "gridd_grid_footer_sidebar_{$sidebar_id}_links_color", '#0f5e97' ),
		'--gridd-footer-padding'                         => get_theme_mod( 'gridd_grid_footer_padding', '1em' ),
	]
);
?>

<div class="gridd-tp gridd-tp-footer_sidebar_<?php echo esc_attr( $sidebar_id ); ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-sidebar-' . $sidebar_id );

	/**
	 * Print the sidebar.
	 */
	dynamic_sidebar( "footer_sidebar_{$sidebar_id}" );
	?>
</div>

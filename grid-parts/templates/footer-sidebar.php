<?php
/**
 * Template part for the footer sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;
use Gridd\Theme;

$style = Style::get_instance( "grid-part/footer/sidebar/$sidebar_id" );

// Add stylesheet.
$style->add_file( get_theme_file_path( 'grid-parts/Footer/styles-sidebar.min.css' ) );

// Replace "ID" in the stylesheet.
$style->replace( 'ID', $sidebar_id );
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-footer_sidebar_' . $sidebar_id ], 'wrapper-footer_sidebar_' . $sidebar_id ); ?>>
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

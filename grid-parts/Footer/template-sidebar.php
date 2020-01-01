<?php
/**
 * Template part for the footer sidebars.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

// Print the styles.
\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Footer/styles-sidebar.min.css' ) );

$attributes = [
	'class' => 'gridd-tp gridd-tp-footer_sidebar gridd-tp-footer_sidebar_' . $sidebar_id,
];
if ( get_theme_mod( "footer_sidebar_{$sidebar_id}_custom_options", false ) ) {
	$attributes['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attributes, 'wrapper-footer_sidebar_' . $sidebar_id ); ?>>
	<?php
	/**
	 * Print the sidebar.
	 */
	dynamic_sidebar( "footer_sidebar_{$sidebar_id}" );
	?>
</div>

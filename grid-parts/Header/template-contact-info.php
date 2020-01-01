<?php
/**
 * Template part for the Header Contact Info.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;

\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Header/styles-contact-info.min.css' ) );

$attributes = [
	'class' => 'gridd-tp gridd-tp-header_contact_info',
];
if ( get_theme_mod( 'header_contact_info_custom_options', false ) ) {
	$attributes['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attributes, 'wrapper-header_contact_info' ); ?>>
	<?php
	/**
	 * Print the text entered by the user.
	 */
	echo wp_kses_post( get_theme_mod( 'header_contact_info', __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ) ) );
	?>
</div>

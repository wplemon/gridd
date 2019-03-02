<?php
/**
 * Template part for the Header Contact Info.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Style;

$style = Style::get_instance( 'grid-part/header/contact-info' );
$style->add_file( get_theme_file_path( 'grid-parts/styles/header/contact-info.min.css' ) );
$style->add_vars(
	[
		'--gridd-header-contact-bg'         => get_theme_mod( 'gridd_grid_part_details_header_contact_info_background_color', '#ffffff' ),
		'--gridd-header-contact-font-size'  => get_theme_mod( 'gridd_grid_part_details_header_contact_info_font_size', .85 ) . 'em',
		'--gridd-header-contact-text-align' => get_theme_mod( 'gridd_grid_part_details_header_contact_text_align', 'flex-start' ),
		'--gridd-header-contact-padding'    => get_theme_mod( 'gridd_grid_part_details_header_contact_info_padding', '10px' ),
		'--gridd-header-contact-color'      => get_theme_mod( 'gridd_grid_part_details_header_contact_info_text_color', '#000000' ),
	]
);

/**
 * Print styles.
 */
$style->the_css( 'gridd-inline-css-header-contact-info' );
?>
<div class="gridd-tp gridd-tp-header_contact_info">
	<?php
	/**
	 * Print the text entered by the user.
	 */
	echo wp_kses_post( get_theme_mod( 'gridd_grid_part_details_header_contact_info', __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ) ) );
	?>
</div>

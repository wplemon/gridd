<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;

gridd_add_customizer_section(
	'gridd_grid_part_details_header_contact_info',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Contact Info', 'gridd' ) ),
		'description' => sprintf(
			'<div class="gridd-section-description">%1$s%2$s</div>',
			( ! Gridd::is_pro() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for automatic WCAG-compliant colors suggestion on this section.', 'gridd' ) . '</div>' : '',
			'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/header/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
		),
		'section'     => 'gridd_grid_part_details_header',
		'priority'    => 20,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'editor',
		'settings'    => 'gridd_grid_part_details_header_contact_info',
		'label'       => esc_html__( 'Contact Info Content', 'gridd' ),
		'description' => esc_html__( 'Enter any text you want - usually your contact info or important announcements that you want your visitors to see.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => __( 'Email: <a href="mailto:contact@example.com">contact@example.com</a>. Phone: +1-541-754-3010', 'gridd' ),
		'transport'   => 'postMessage',
		'js_vars'     => [
			[
				'element'  => '.gridd-tp-header_contact_info.gridd-tp',
				'function' => 'html',
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_part_details_header_contact_info_background_color',
		'label'       => esc_html__( 'Background Color', 'gridd' ),
		'description' => esc_html__( 'Select a background color for this grid-part.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => '#ffffff',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-header-contact-bg',
		'choices'     => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'kirki-wcag-tc',
		'label'       => esc_html__( 'Text Color', 'gridd' ),
		'description' => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
		'settings'    => 'gridd_grid_part_details_header_contact_info_text_color',
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'choices'     => [
			'setting' => 'gridd_grid_part_details_header_contact_info_background_color',
		],
		'default'     => '#000000',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-header-contact-color',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_part_details_header_contact_info_font_size',
		'label'       => esc_html__( 'Font Size', 'gridd' ),
		'description' => esc_html__( 'Controls the font-size for this grid-part. The value selected here is relevant to your body font-size, so a value of 1em will be the same size as your content.', 'gridd' ),
		'tooltip'     => gridd()->customizer->get_text( 'related-font-size' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => .85,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-header-contact-font-size', '$em' ],
		'choices'     => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_part_details_header_contact_info_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => __( 'The padding for this grid-part. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => '10px',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-header-contact-padding',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'radio-buttonset',
		'settings'    => 'gridd_grid_part_details_header_contact_text_align',
		'label'       => esc_html__( 'Text Align', 'gridd' ),
		'description' => esc_html__( 'Select how the text will be aligned.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header_contact_info',
		'default'     => 'flex-start',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-header-contact-text-align',
		'choices'     => [
			'flex-start' => esc_html__( 'Left', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Right', 'gridd' ),
		],
	]
);

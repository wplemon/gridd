<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Theme;
use Gridd\Grid_Part\Footer;
use Gridd\Customizer;

// Include Social Icons Definitions.
require_once get_template_directory() . '/inc/social-icons.php';

new \Kirki\Section(
	'gridd_grid_part_details_footer_social_media',
	[
		/* translators: The grid-part label. */
		'title' => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Footer Contact Info', 'gridd' ) ),
		'type'  => 'kirki-outer',
	]
);

Customizer::add_field(
	[
		'type'            => 'repeater',
		'settings'        => 'gridd_grid_part_details_footer_social_icons',
		'label'           => esc_html__( 'Social Media Links', 'gridd' ),
		'description'     => esc_html__( 'Add, remove and reorder your social links.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_footer_social_media',
		'default'         => [],
		'row_label'       => [
			'type'  => 'field',
			'field' => 'icon',
		],
		'button_label'    => esc_html__( 'Add Icon', 'gridd' ),
		'fields'          => [
			'icon' => [
				'type'        => 'select',
				'label'       => esc_html__( 'Select Icon', 'gridd' ),
				'description' => esc_html__( 'Choose a social-network to add its icon.', 'gridd' ),
				'default'     => '',
				'choices'     => gridd_social_icons_svg( 'keys_only' ),
			],
			'url'  => [
				'type'        => 'text',
				'label'       => esc_html__( 'Link URL', 'gridd' ),
				'description' => esc_html__( 'Enter the URL for your profile/page on the social network.', 'gridd' ),
				'default'     => '',
			],
		],
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_part_details_footer_social_icons_template' => [
				'selector'            => '.gridd-tp-footer_social_media',
				'container_inclusive' => false,
				'render_callback'     => function() {
					Theme::get_template_part( 'grid-parts/templates/footer-social-media' );
				},
			],
		],
	]
);

new \Kirki\Field\Slider(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_part_details_footer_social_icons_size',
		'label'     => esc_html__( 'Size', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_social_media',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => '--ft-si-sz',
		'choices'   => [
			'min'    => .3,
			'max'    => 3,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

new \Kirki\Field\Slider(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_part_details_footer_social_icons_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Controls how large the clickable area will be, and also the spacing between icons.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer_social_media',
		'default'     => .5,
		'transport'   => 'postMessage',
		'css_vars'    => '--ft-si-pd',
		'choices'     => [
			'min'    => 0,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

new \Kirki\Field\Color(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_footer_social_icons_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_social_media',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--ft-si-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

new \Kirki\Field\Color(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_footer_social_icons_icons_color',
		'label'     => esc_html__( 'Icons Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_social_media',
		'default'   => '#000000',
		'transport' => 'postMessage',
		'css_vars'  => '--ft-si-cl',
		'choices'   => [
			'alpha' => true,
		],
	]
);

new \Kirki\Field\Radio_Buttonset(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_part_details_footer_social_icons_icons_text_align',
		'label'             => esc_html__( 'Icons Alignment', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_social_media',
		'default'           => 'flex-end',
		'transport'         => 'postMessage',
		'css_vars'          => '--ft-si-ta',
		'choices'           => [
			'flex-start' => esc_html__( 'Left', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'flex-start' !== $value && 'flex-end' !== $value && 'center' !== $value ) ? 'flex-end' : $value;
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

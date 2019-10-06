<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Theme;
use Gridd\Grid_Part\Header;
use Gridd\Customizer;

if ( ! function_exists( 'gridd_social_icons_svg' ) ) {

	// Include Social Icons Definitions.
	require_once get_template_directory() . '/inc/social-icons.php';
}

Customizer::add_outer_section(
	'grid_part_details_social_media',
	[
		/* translators: The grid-part label. */
		'title' => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Header Contact Info', 'gridd' ) ),
	]
);

Customizer::add_field(
	[
		'type'            => 'repeater',
		'settings'        => 'header_social_icons',
		'label'           => esc_html__( 'Social Media Links', 'gridd' ),
		'description'     => esc_html__( 'Add, remove and reorder your social links.', 'gridd' ),
		'section'         => 'grid_part_details_social_media',
		'default'         => [],
		'row_label'       => [
			'type'  => 'field',
			'field' => 'icon',
		],
		'priority'        => 10,
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
			'grid_part_details_social_icons_template' => [
				'selector'            => '.gridd-tp-social_media',
				'container_inclusive' => false,
				'render_callback'     => function() {
					Theme::get_template_part( 'grid-parts/templates/header-social-media' );
				},
			],
		],
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'header_social_icons_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_social_media',
		'default'   => '#ffffff',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-social_media',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'alpha' => true,
		],
		'priority'  => 20,
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'header_social_icons_icons_color',
		'label'     => esc_html__( 'Icons Color', 'gridd' ),
		'section'   => 'grid_part_details_social_media',
		'default'   => '#000000',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-social_media',
				'property' => '--cl',
			],
		],
		'choices'   => [
			'alpha' => true,
		],
		'priority'  => 30,
	]
);

Customizer::add_field(
	[
		'type'      => 'slider',
		'settings'  => 'header_social_icons_size',
		'label'     => esc_html__( 'Size', 'gridd' ),
		'section'   => 'grid_part_details_social_media',
		'default'   => 1,
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-social_media',
				'property' => '--sz',
			],
		],
		'priority'  => 40,
		'choices'   => [
			'min'    => .3,
			'max'    => 3,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'        => 'slider',
		'settings'    => 'header_social_icons_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => esc_html__( ' Controls how large the clickable area will be and the spacing between icons.', 'gridd' ),
		'section'     => 'grid_part_details_social_media',
		'default'     => .5,
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-social_media',
				'property' => '--pd',
			],
		],
		'priority'    => 50,
		'choices'     => [
			'min'    => 0,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'header_social_icons_icons_text_align',
		'label'             => esc_html__( 'Icons Alignment', 'gridd' ),
		'section'           => 'grid_part_details_social_media',
		'default'           => 'flex-end',
		'priority'          => 60,
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-social_media',
				'property' => '--ta',
			],
		],
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

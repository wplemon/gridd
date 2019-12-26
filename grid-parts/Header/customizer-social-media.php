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

new \Kirki\Section(
	'header_social',
	[
		'panel'           => 'theme_options',
		'title'           => esc_html__( 'Header Social Media', 'gridd' ),
		'type'            => 'kirki-expanded',
		'priority'        => 60,
		'active_callback' => function() {
			return \Gridd\Customizer::is_section_active_part( 'social_media' );
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'header_social_advanced',
		'section'   => 'header_social',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

new \Kirki\Field\Repeater(
	[
		'settings'        => 'header_social_icons',
		'label'           => esc_html__( 'Social Media Links', 'gridd' ),
		'description'     => esc_html__( 'Add, remove and reorder your social links.', 'gridd' ),
		'section'         => 'header_social',
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
					Theme::get_template_part( 'grid-parts/Header/template-social-media' );
				},
			],
		],
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'header_social_icons_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'header_social',
		'default'         => '#ffffff',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-social_media.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 20,
		'active_callback' => function() {
			return get_theme_mod( 'header_social_advanced' );
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'header_social_icons_icons_color',
		'label'           => esc_html__( 'Icons Color', 'gridd' ),
		'section'         => 'header_social',
		'default'         => '#000000',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-social_media.custom-options',
				'property' => '--cl',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 30,
		'active_callback' => function() {
			return get_theme_mod( 'header_social_advanced' );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'header_social_icons_size',
		'label'           => esc_html__( 'Size', 'gridd' ),
		'section'         => 'header_social',
		'default'         => 1,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-social_media.custom-options',
				'property' => '--fsr',
			],
		],
		'priority'        => 40,
		'choices'         => [
			'min'    => .3,
			'max'    => 3,
			'step'   => .01,
			'suffix' => 'em',
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_social_advanced' );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'header_social_icons_padding',
		'label'           => esc_html__( 'Padding', 'gridd' ),
		'description'     => esc_html__( ' Controls how large the clickable area will be and the spacing between icons.', 'gridd' ),
		'section'         => 'header_social',
		'default'         => .5,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-social_media.custom-options',
				'property' => '--pd',
			],
		],
		'priority'        => 50,
		'choices'         => [
			'min'    => 0,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_social_advanced' );
		},
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'header_social_icons_icons_text_align',
		'label'             => esc_html__( 'Icons Alignment', 'gridd' ),
		'section'           => 'header_social',
		'default'           => 'flex-end',
		'priority'          => 60,
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-social_media.custom-options',
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
		'active_callback'   => function() {
			return get_theme_mod( 'header_social_advanced' );
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

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
	'footer_social_media',
	[
		'title'           => esc_html__( 'Footer Social Media', 'gridd' ),
		'priority'        => 100,
		// 'type'            => 'kirki-expanded',
		'panel'           => 'theme_options',
		'active_callback' => function() {
			return \Gridd\Customizer::is_section_active_part( 'footer_social_media' );
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'footer_social_media_custom_options',
		'section'   => 'footer_social_media',
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
		'settings'        => 'footer_social_icons',
		'label'           => esc_html__( 'Social Media Links', 'gridd' ),
		'description'     => esc_html__( 'Add, remove and reorder your social links.', 'gridd' ),
		'section'         => 'footer_social_media',
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
			'footer_social_icons_template' => [
				'selector'            => '.gridd-tp-footer_social_media',
				'container_inclusive' => false,
				'render_callback'     => function() {
					Theme::get_template_part( 'grid-parts/Footer/template-social-media' );
				},
			],
		],
		'priority'        => 10,
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'footer_social_icons_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'footer_social_media',
		'default'         => '#ffffff',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_social_media.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 20,
		'active_callback' => function() {
			return get_theme_mod( 'footer_social_media_custom_options', false );
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'footer_social_icons_icons_color',
		'label'           => esc_html__( 'Icons Color', 'gridd' ),
		'section'         => 'footer_social_media',
		'default'         => '#000000',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_social_media.custom-options',
				'property' => '--cl',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'        => 30,
		'active_callback' => function() {
			return get_theme_mod( 'footer_social_media_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'footer_social_icons_size',
		'label'           => esc_html__( 'Size', 'gridd' ),
		'section'         => 'footer_social_media',
		'default'         => 1,
		'transport'       => 'postMessage',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_social_media.custom-options',
				'property' => '--sz',
			],
		],
		'choices'         => [
			'min'    => .3,
			'max'    => 3,
			'step'   => .01,
			'suffix' => 'em',
		],
		'priority'        => 40,
		'active_callback' => function() {
			return get_theme_mod( 'footer_social_media_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'footer_social_icons_padding',
		'label'           => esc_html__( 'Padding', 'gridd' ),
		'description'     => esc_html__( 'Controls how large the clickable area will be, and also the spacing between icons.', 'gridd' ),
		'section'         => 'footer_social_media',
		'default'         => .5,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer_social_media.custom-options',
				'property' => '--pdr',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
		'priority'        => 50,
		'active_callback' => function() {
			return get_theme_mod( 'footer_social_media_custom_options', false );
		},
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'footer_social_icons_icons_text_align',
		'label'             => esc_html__( 'Icons Alignment', 'gridd' ),
		'section'           => 'footer_social_media',
		'default'           => 'flex-end',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-footer_social_media',
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
		'priority'          => 60,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

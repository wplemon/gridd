<?php
/**
 * Gridd Customizer
 *
 * @package Gridd
 *
 * phpcs:ignore WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Customizer\Sanitize;
$sanitization = new Sanitize();

/**
 * Move the background controls to the grid section.
 *
 * @since 1.0
 * @param WP_Customize The WordPress Customizer main object.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {
		// Move the background-image control.
		$wp_customize->get_control( 'background_image' )->active_callback      = '__return_false';
		$wp_customize->get_control( 'background_preset' )->active_callback     = '__return_false';
		$wp_customize->get_control( 'background_position' )->active_callback   = '__return_false';
		$wp_customize->get_control( 'background_size' )->active_callback       = '__return_false';
		$wp_customize->get_control( 'background_repeat' )->active_callback     = '__return_false';
		$wp_customize->get_control( 'background_attachment' )->active_callback = '__return_false';
	}
);

/**
 * Add the "Theme Options" panel.
 *
 * @since 2.0.0
 */
new \Kirki\Panel(
	'theme_options',
	[
		'title'    => esc_html__( 'Theme Options', 'gridd' ),
		'priority' => 5,
	]
);

new \Kirki\Section(
	'theme_options',
	[
		'title'    => esc_html__( 'General Options', 'gridd' ),
		'priority' => -100,
		'type'     => 'kirki-expanded',
		'panel'    => 'theme_options',
	]
);

new \WPLemon\Field\PaletteBuilder(
	[
		'settings'          => 'custom_color_palette',
		'label'             => esc_attr__( 'Color Palette', 'gridd' ),
		'description'       => esc_html__( 'Colors selected here will affect the palette available in all controls, as well as the palette in the WordPress editor.', 'gridd' ),
		'section'           => 'colors',
		'default'           => \Gridd\Theme::get_color_palette( true ),
		'priority'          => 10,
		'sanitize_callback' => function( $val ) {
			if ( is_string( $val ) ) {
				$val = json_decode( $val );
			}
			$value = [];
			foreach ( $val as $key => $item ) {
				$value[] = [
					'name'  => sanitize_text_field( $item['slug'] ),
					'slug'  => sanitize_text_field( $item['slug'] ),
					'color' => sanitize_hex_color( $item['color'] ),
				];
			}
			return $value;
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'          => 'background_color',
		'label'             => esc_html__( 'Background Color', 'gridd' ),
		'section'           => 'colors',
		'default'           => '#F5F7F9',
		'transport'         => 'postMessage',
		'priority'          => 20,
		'choices'           => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'output'            => [
			[
				'element'           => ':root',
				'property'          => '--bg',
				'sanitize_callback' => function( $value ) {
					if ( false === strpos( $value, '(' ) ) {
						return '#' . str_replace( '#', '', $value );
					}
					return $value;
				},
			],
			[
				'element'           => '.block-editor .edit-post-visual-editor.editor-styles-wrapper,.edit-post-visual-editor.editor-styles-wrapper',
				'property'          => '--bg',
				'context'           => [ 'editor' ],
				'sanitize_callback' => function( $value ) {
					if ( false === strpos( $value, '(' ) ) {
						return '#' . str_replace( '#', '', $value );
					}
					return $value;
				},
			],
		],
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'colors',
		'priority'          => 30,
		'default'           => '#000000',
		'output'            => [
			[
				'element'  => ':root',
				'property' => '--cl',
			],
			[
				'element'  => '.block-editor .edit-post-visual-editor.editor-styles-wrapper,.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--cl',
				'context'  => [ 'editor' ],
			],
		],
		'transport'         => 'auto',
		'choices'           => [
			'backgroundColor' => 'background_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \WPLemon\Field\WCAGLinkColor(
	[
		'settings'          => 'links_color',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'description'       => esc_html__( 'Select the hue for you links. The color will be auto-calculated to ensure maximum readability according to WCAG.', 'gridd' ),
		'section'           => 'colors',
		'transport'         => 'auto',
		'priority'          => 40,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#0f5e97',
		'choices'           => [
			'backgroundColor' => 'background_color',
			'textColor'       => 'text_color',
			'linksUnderlined' => true,
			'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
		],
		'output'            => [
			[
				'element'  => ':root',
				'property' => '--lc',
			],
			[
				'element'  => '.block-editor .edit-post-visual-editor.editor-styles-wrapper,.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--lc',
				'context'  => [ 'editor' ],
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'target_color_compliance',
		'label'             => esc_attr__( 'Color Accessibility Target Compliance', 'gridd' ),
		'description'       => sprintf(
			'<details>%1$s<ul><li>%2$s</li><li>%3$s</li><li>%4$s</li></ul></details>',
			esc_html__( 'Select how link colors will be calculated.', 'gridd' ),
			__( '<strong>Auto</strong>: Automatically get colors with maximum contrast to get as close to a higher accessibility standard as possible. Similar to AAA compliance, with a fallback to above-AA colors if AAA is not achievable.', 'gridd' ),
			__( '<strong>AAA</strong>: Targeting for "AAA" compliance results in less intense but easier to read colors.', 'gridd' ),
			__( '<strong>AA</strong>: Targeting for "AA" compliance will result in more vibrant colors while still maintaining a readable color contrast.', 'gridd' )
		),
		'section'           => 'colors',
		'default'           => 'auto',
		'transport'         => 'postMessage',
		'priority'          => 50,
		'choices'           => [
			'auto' => esc_html__( 'Auto', 'gridd' ),
			'AAA'  => 'AAA',
			'AA'   => 'AA',
		],
		'sanitize_callback' => function( $value ) {
			return ( 'auto' === $value || 'AAA' === $value || 'AA' === $value ) ? $value : 'auto';
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'          => 'background_color',
		'label'             => esc_html__( 'Background Color', 'gridd' ),
		'section'           => 'colors',
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'priority'          => 60,
		'choices'           => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
	]
);


/**
 * Add the config.
 */
\Kirki::add_config(
	'gridd',
	[
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	]
);

// Add customizer sections & settings.
require_once get_template_directory() . '/inc/customizer/grid.php';
require_once get_template_directory() . '/inc/customizer/typography.php';
require_once get_template_directory() . '/inc/customizer/mobile.php';
require_once get_template_directory() . '/inc/customizer/edd.php';
require_once get_template_directory() . '/inc/customizer/features.php';
require_once get_template_directory() . '/inc/customizer/static-front-page.php';
require_once get_template_directory() . '/inc/customizer/woocommerce.php';

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

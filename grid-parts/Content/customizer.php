<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

new \Kirki\Section(
	'grid_part_details_content',
	[
		/* translators: The grid-part label. */
		'title'    => esc_html__( 'Content', 'gridd' ),
		'priority' => 20,
		'type'     => 'kirki-expanded',
		'panel'    => 'theme_options',
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'content_custom_options',
		'section'   => 'grid_part_details_content',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'content_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'grid_part_details_content',
		'default'         => '#F5F7F9',
		'output'          => [
			[
				'element'  => '.gridd-tp-content.custom-options',
				'property' => '--bg',
			],
			[
				'element'  => '.block-editor .edit-post-visual-editor.editor-styles-wrapper,.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--bg',
				'context'  => [ 'editor' ],
			],
		],
		'transport'       => 'auto',
		'priority'        => 10,
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'gridd_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'grid_part_details_content',
		'priority'          => 20,
		'default'           => '#000000',
		'output'            => [
			[
				'element'  => '.gridd-tp-content.custom-options',
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
			'backgroundColor' => 'content_background_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGLinkColor(
	[
		'settings'          => 'gridd_links_color',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'description'       => esc_html__( 'Select the hue for you links. The color will be auto-calculated to ensure maximum readability according to WCAG.', 'gridd' ),
		'section'           => 'grid_part_details_content',
		'transport'         => 'auto',
		'priority'          => 30,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#0f5e97',
		'choices'           => [
			'backgroundColor' => 'content_background_color',
			'textColor'       => 'gridd_text_color',
			'linksUnderlined' => true,
			'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
		],
		'output'            => [
			[
				'element'  => '.gridd-tp-content.custom-options',
				'property' => '--lc',
			],
			[
				'element'  => '.block-editor .edit-post-visual-editor.editor-styles-wrapper,.edit-post-visual-editor.editor-styles-wrapper',
				'property' => '--lc',
				'context'  => [ 'editor' ],
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'  => 'content_max_width',
		'label'     => esc_html__( 'Content Maximum Width', 'gridd' ),
		'section'   => 'grid_part_details_content',
		'default'   => '45em',
		'output'    => [
			[
				'element'  => ':root',
				'property' => '--cmw',
			],
		],
		'transport' => 'auto',
		'priority'  => 50,
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'content_padding_horizontal',
		'label'           => esc_html__( 'Horizontal Content Padding', 'gridd' ),
		'section'         => 'grid_part_details_content',
		'default'         => 1,
		'output'          => [
			[
				'element'       => '.gridd-tp-content.custom-options',
				'property'      => '--pd-h',
				'value_pattern' => '$em',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'transport'       => 'auto',
		'priority'        => 60,
		'active_callback' => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'content_padding_top',
		'label'           => esc_html__( 'Top Content Padding', 'gridd' ),
		'section'         => 'grid_part_details_content',
		'default'         => 1,
		'output'          => [
			[
				'element'       => '.gridd-tp-content.custom-options',
				'property'      => '--pd-t',
				'value_pattern' => '$em',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'transport'       => 'auto',
		'priority'        => 70,
		'active_callback' => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

new \Kirki\Field\Slider(
	[
		'settings'        => 'content_padding_bottom',
		'label'           => esc_html__( 'Bottom Content Padding', 'gridd' ),
		'section'         => 'grid_part_details_content',
		'default'         => 1,
		'output'          => [
			[
				'element'       => '.gridd-tp-content.custom-options',
				'property'      => '--pd-b',
				'value_pattern' => '$em',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 10,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'transport'       => 'auto',
		'priority'        => 80,
		'active_callback' => function() {
			return get_theme_mod( 'content_custom_options', false );
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

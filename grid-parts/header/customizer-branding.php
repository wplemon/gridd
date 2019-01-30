<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

gridd_add_customizer_section(
	'gridd_grid_part_details_header_branding',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Site Branding', 'gridd' ) ),
		'description' => Customizer::section_description( false, 'https://wplemon.com/documentation/gridd/grid-parts/header/' ),
		'section'     => 'gridd_grid_part_details_header',
	]
);

/**
 * Focus on title_tagline section.
 */
gridd_add_customizer_field(
	[
		'settings' => 'gridd_logo_focus_on_core_section',
		'type'     => 'custom',
		'label'    => '',
		'section'  => 'gridd_grid_part_details_header_branding',
		'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="title_tagline">' . esc_html__( 'Click here to edit your site identity', 'gridd' ) . '</button></div>',
	]
);

/**
 * Focus on title_tagline section.
 */
gridd_add_customizer_field(
	[
		'settings' => 'gridd_logo_focus_on_grid_part_section',
		'type'     => 'custom',
		'label'    => '',
		'section'  => 'title_tagline',
		'priority' => 1,
		'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="gridd_grid_part_details_header_branding">' . esc_html__( 'Click here to edit the branding grid-part', 'gridd' ) . '</button></div>',
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_header_branding_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		// 'description' => esc_html__( 'Select the background color for this grid-part.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_branding',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-branding-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'text',
		'settings'  => 'gridd_grid_header_branding_padding',
		'label'     => esc_html__( 'Padding', 'gridd' ),
		'tooltip'   => __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_branding',
		'default'   => '0.5em',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-branding-padding',
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_logo_max_width',
		'type'            => 'slider',
		'label'           => esc_html__( 'Logo Maximum Width', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'default'         => 100,
		'choices'         => [
			'min'    => 10,
			'max'    => 600,
			'step'   => 1,
			'suffix' => 'px',
		],
		'transport'       => 'postMessage',
		'css_vars'        => [ '--gridd-logo-max-width', '$px' ],
		'active_callback' => [
			[
				'setting'  => 'custom_logo',
				'operator' => '!==',
				'value'    => '',
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_branding_sitename_typography',
		'type'            => 'typography',
		'label'           => esc_html__( 'Site-Title Typography', 'gridd' ),
		// 'description'     => esc_html__( 'Please select typography settings for your site-title.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'default'         => [
			'font-family' => 'Noto Serif',
			'font-weight' => 700,
			'font-size'   => '32px',
			'color'       => '#000000',
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element' => '.gridd-tp-header_branding .site-title',
			],
			[
				'element'  => [ '.gridd-tp-header_branding .site-title a', '.gridd-tp-header_branding .site-title a:hover', '.gridd-tp-header_branding .site-title a:focus', '.gridd-tp-header_branding .site-title a:visited' ],
				'property' => 'color',
				'choice'   => 'color',
			],
			[
				'element'  => [ '.gridd-tp-header_branding .site-description' ],
				'property' => 'line-height',
				'choice'   => 'font-size',
			],
		],
		'active_callback' => 'display_header_text',
		'choices'         => [
			'fonts' => [
				'google' => [ 'popularity' ],
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_branding_tagline_typography',
		'type'            => 'typography',
		'label'           => esc_html__( 'Tagline Typography', 'gridd' ),
		// 'description'     => esc_html__( 'Please select typography settings for your tagline.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'default'         => [
			'font-family' => 'Noto Serif',
			'font-weight' => 'regular',
			'font-size'   => '16px',
			'color'       => '#000000',
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element' => '.gridd-tp-header_branding .site-description',
			],
		],
		'active_callback' => 'display_header_text',
		'choices'         => [
			'fonts' => [
				'google' => [ 'popularity' ],
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_branding_inline',
		'type'            => 'checkbox',
		'label'           => esc_html__( 'Inline Elements', 'gridd' ),
		'description'     => esc_html__( 'Enable this option to show the branding elements inline instead of one below the other.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'default'         => false,
		'transport'       => 'postMessage',
		'active_callback' => 'display_header_text',
		'partial_refresh' => [
			'gridd_branding_inline_rendered' => [
				'selector'            => '.gridd-tp.gridd-tp-header',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'header' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_branding_inline_spacing',
		'type'            => 'slider',
		'label'           => esc_html__( 'Spacing between elements', 'gridd' ),
		'tooltip'         => esc_html__( 'This value is relevant to the site-title font-size.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'default'         => .5,
		'transport'       => 'postMessage',
		'css_vars'        => [ '--gridd-branding-elements-padding', '$em' ],
		'choices'         => [
			'min'    => 0,
			'max'    => 5,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'active_callback' => 'display_header_text',
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'radio-buttonset',
		'settings'  => 'gridd_grid_header_branding_horizontal_align',
		'label'     => esc_html__( 'Horizontal Alignment', 'gridd' ),
		// 'description' => esc_html__( 'Choose the horizontal alignment for your branding', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_branding',
		'default'   => 'left',
		'transport' => 'auto',
		'css_vars'  => '--gridd-branding-horizontal-align',
		'transport' => 'postMessage',
		'choices'   => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'radio-buttonset',
		'settings'  => 'gridd_grid_header_branding_vertical_align',
		'label'     => esc_html__( 'Vertical Alignment', 'gridd' ),
		// 'description' => esc_html__( 'Choose the vertical alignment for your branding', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header_branding',
		'default'   => 'center',
		'transport' => 'auto',
		'css_vars'  => '--gridd-branding-vertical-align',
		'transport' => 'postMessage',
		'choices'   => [
			'flex-start' => esc_html__( 'Top', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Bottom', 'gridd' ),
		],
	]
);

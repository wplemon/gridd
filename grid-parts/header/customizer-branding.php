<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

gridd_add_customizer_outer_section(
	'gridd_grid_part_details_header_branding',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Site Branding', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_header_branding',
			[
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/header/branding/',
			]
		),
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
		'section'   => 'gridd_grid_part_details_header_branding',
		'priority'  => 10,
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-branding-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

/**
 * Tweak the header-textcolor control.
 *
 * @since 1.0.3
 * @param object $wp_customize The WordPress Customizer instance.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {
		$wp_customize->get_control( 'header_textcolor' )->section     = 'gridd_grid_part_details_header_branding';
		$wp_customize->get_control( 'header_textcolor' )->label       = esc_html__( 'Text Color', 'gridd' );
		$wp_customize->get_control( 'header_textcolor' )->description = esc_html__( 'Select the text color for your site title & tagline.', 'gridd' );
		$wp_customize->get_setting( 'header_textcolor' )->transport   = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->default     = '#000000';
		$wp_customize->get_control( 'header_textcolor' )->priority    = 20;
	}
);

gridd_add_customizer_field(
	[
		'type'              => 'text',
		'settings'          => 'gridd_grid_header_branding_padding',
		'label'             => esc_html__( 'Padding', 'gridd' ),
		'tooltip'           => __( 'For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'           => 'gridd_grid_part_details_header_branding',
		'priority'          => 30,
		'default'           => '0.5em',
		'transport'         => 'postMessage',
		'css_vars'          => '--gridd-branding-padding',
		'sanitize_callback' => 'esc_attr', // Though not exactly accurate, in this case it sanitizes the CSS value properly.
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_logo_max_width',
		'type'            => 'slider',
		'label'           => esc_html__( 'Logo Maximum Width', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'priority'        => 40,
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
		'settings'        => 'gridd_branding_typography',
		'type'            => 'typography',
		'label'           => esc_html__( 'Site Title & Tagline Typography', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'priority'        => 50,
		'default'         => [
			'font-family' => 'Noto Serif',
			'font-weight' => 700,
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element' => [ '.site-title', '.site-title a', '.site-description' ],
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
		'settings'        => 'gridd_branding_sitetitle_size',
		'type'            => 'slider',
		'label'           => esc_html__( 'Site Title Font-Size', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'priority'        => 60,
		'default'         => 2,
		'transport'       => 'postMessage',
		'active_callback' => 'display_header_text',
		'css_vars'        => [ '--gridd-sitetitle-size', '$em' ],
		'choices'         => [
			'min'    => 1,
			'max'    => 10,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'settings'        => 'gridd_branding_tagline_size',
		'type'            => 'slider',
		'label'           => esc_html__( 'Site Tagline Font-Size', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header_branding',
		'priority'        => 70,
		'default'         => 1,
		'transport'       => 'postMessage',
		'active_callback' => 'display_header_text',
		'css_vars'        => [ '--gridd-tagline-size', '$em' ],
		'choices'         => [
			'min'    => 1,
			'max'    => 5,
			'step'   => .01,
			'suffix' => 'em',
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
		'priority'        => 80,
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
		'priority'        => 90,
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
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_header_branding_horizontal_align',
		'label'             => esc_html__( 'Horizontal Alignment', 'gridd' ),
		'section'           => 'gridd_grid_part_details_header_branding',
		'priority'          => 100,
		'default'           => 'left',
		'transport'         => 'auto',
		'css_vars'          => '--gridd-branding-horizontal-align',
		'transport'         => 'postMessage',
		'choices'           => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'left' !== $value && 'right' !== $value && 'center' !== $value ) ? 'left' : $value;
		},
	]
);

gridd_add_customizer_field(
	[
		'type'              => 'radio-buttonset',
		'settings'          => 'gridd_grid_header_branding_vertical_align',
		'label'             => esc_html__( 'Vertical Alignment', 'gridd' ),
		'section'           => 'gridd_grid_part_details_header_branding',
		'priority'          => 110,
		'default'           => 'center',
		'transport'         => 'auto',
		'css_vars'          => '--gridd-branding-vertical-align',
		'transport'         => 'postMessage',
		'choices'           => [
			'flex-start' => esc_html__( 'Top', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Bottom', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'flex-start' !== $value && 'flex-end' !== $value && 'center' !== $value ) ? 'center' : $value;
		},
	]
);

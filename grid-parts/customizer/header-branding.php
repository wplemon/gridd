<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

add_action(
	'customize_register',
	/**
	 * Tweak default customizer controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function( $wp_customize ) {

		// Header textcolor.
		$wp_customize->get_control( 'header_textcolor' )->section     = 'grid_part_details_header_branding';
		$wp_customize->get_control( 'header_textcolor' )->label       = esc_html__( 'Text Color', 'gridd' );
		$wp_customize->get_control( 'header_textcolor' )->description = esc_html__( 'Select the text color for your site title & tagline.', 'gridd' );
		$wp_customize->get_setting( 'header_textcolor' )->transport   = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->default     = '#000000';
		$wp_customize->get_control( 'header_textcolor' )->priority    = 20;

		// Blog name.
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';

		// Blog description.
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		// Custom logo.
		$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';

		// Add partial refreshes for sitename & description.
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => function() {
						bloginfo( 'name' );
					},
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => function() {
						bloginfo( 'description' );
					},
				)
			);
		}
	}
);

Customizer::add_outer_section(
	'grid_part_details_header_branding',
	[
		'title' => esc_html__( 'Site Branding', 'gridd' ),
	]
);

/**
 * Focus on title_tagline section.
 */
Customizer::add_field(
	[
		'settings' => 'gridd_logo_focus_on_core_section',
		'type'     => 'custom',
		'label'    => '',
		'section'  => 'grid_part_details_header_branding',
		'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="title_tagline">' . esc_html__( 'Click here to edit your site identity', 'gridd' ) . '</button></div>',
	]
);

/**
 * Focus on title_tagline section.
 */
Customizer::add_field(
	[
		'settings' => 'gridd_logo_focus_on_grid_part_section',
		'type'     => 'custom',
		'label'    => '',
		'section'  => 'title_tagline',
		'priority' => 1,
		'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="grid_part_details_header_branding">' . esc_html__( 'Click here to edit the branding grid-part', 'gridd' ) . '</button></div>',
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'gridd_grid_header_branding_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_header_branding',
		'priority'  => 10,
		'default'   => '#ffffff',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'formComponent' => 'TwitterPicker',
			'colors'        => [ '#FFFFFF', '#fffcea', '#F9F9F9', '#f7f6e3', '#f7f7f7', '#f4f4e1', '#1A1A1A', '#000000', '#FF6900', '#FCB900', '#7BDCB5', '#00D084', '#8ED1FC', '#0693E3', '#ABB8C3', '#EB144C', '#F78DA7', '#9900EF' ],
		],
	]
);

Customizer::add_field(
	[
		'type'              => 'dimension',
		'settings'          => 'gridd_grid_header_branding_padding',
		'label'             => esc_html__( 'Padding', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => sprintf(
					/* translators: Link properties. */
					__( 'Use any valid CSS value. For details on how padding works, please refer to <a %s>this article</a>.', 'gridd' ),
					'href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow"'
				),
			]
		),
		'section'           => 'grid_part_details_header_branding',
		'priority'          => 30,
		'default'           => '0',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--pd',
			],
		],
		'sanitize_callback' => 'esc_attr', // Though not exactly accurate, in this case it sanitizes the CSS value properly.
	]
);

Customizer::add_field(
	[
		'settings'        => 'gridd_logo_max_width',
		'type'            => 'slider',
		'label'           => esc_html__( 'Logo Maximum Width', 'gridd' ),
		'section'         => 'grid_part_details_header_branding',
		'priority'        => 40,
		'default'         => 100,
		'choices'         => [
			'min'    => 10,
			'max'    => 600,
			'step'   => 1,
			'suffix' => 'px',
		],
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--mw',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'custom_logo',
				'operator' => '!==',
				'value'    => '',
			],
		],
	]
);

Customizer::add_field(
	[
		'settings'  => 'gridd_branding_typography',
		'type'      => 'typography',
		'label'     => esc_html__( 'Site Title & Tagline Typography', 'gridd' ),
		'section'   => 'grid_part_details_header_branding',
		'priority'  => 50,
		'default'   => [
			'font-family' => '',
			'font-weight' => 700,
		],
		'transport' => 'auto',
		'output'    => [
			[
				'element' => [ '.site-title', '.site-title a', '.site-description' ],
			],
		],
		'choices'   => [
			'fonts' => [
				'google'   => [ 'popularity' ],
				'standard' => [],
			],
		],
	]
);

Customizer::add_field(
	[
		'settings'        => 'gridd_branding_sitetitle_size',
		'type'            => 'slider',
		'label'           => esc_html__( 'Site Title Font-Size', 'gridd' ),
		'section'         => 'grid_part_details_header_branding',
		'priority'        => 60,
		'default'         => 2,
		'active_callback' => 'display_header_text',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--sts',
			],
		],
		'choices'         => [
			'min'    => 1,
			'max'    => 10,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'settings'        => 'gridd_branding_tagline_size',
		'type'            => 'slider',
		'label'           => esc_html__( 'Site Tagline Font-Size', 'gridd' ),
		'section'         => 'grid_part_details_header_branding',
		'priority'        => 70,
		'default'         => 1,
		'active_callback' => 'display_header_text',
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--tls',
			],
		],
		'choices'         => [
			'min'    => 1,
			'max'    => 5,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

Customizer::add_field(
	[
		'settings'        => 'gridd_branding_inline',
		'type'            => 'checkbox',
		'label'           => esc_html__( 'Inline Elements', 'gridd' ),
		'description'     => esc_html__( 'Enable this option to show the branding elements inline instead of one below the other.', 'gridd' ),
		'section'         => 'grid_part_details_header_branding',
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

Customizer::add_field(
	[
		'settings'        => 'gridd_branding_inline_spacing',
		'type'            => 'slider',
		'label'           => esc_html__( 'Spacing between elements', 'gridd' ),
		'description'     => esc_html__( 'This value is relative to the site-title font-size.', 'gridd' ),
		'section'         => 'grid_part_details_header_branding',
		'priority'        => 90,
		'default'         => .5,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--epd',
			],
		],
		'choices'         => [
			'min'    => 0,
			'max'    => 5,
			'step'   => 0.01,
			'suffix' => 'em',
		],
		'active_callback' => 'display_header_text',
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_header_branding_horizontal_align',
		'label'             => esc_html__( 'Horizontal Alignment', 'gridd' ),
		'section'           => 'grid_part_details_header_branding',
		'priority'          => 100,
		'default'           => 'left',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--ha',
			],
		],
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

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_header_branding_vertical_align',
		'label'             => esc_html__( 'Vertical Alignment', 'gridd' ),
		'section'           => 'grid_part_details_header_branding',
		'priority'          => 110,
		'default'           => 'center',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header_branding',
				'property' => '--va',
			],
		],
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

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

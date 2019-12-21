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
		$wp_customize->get_control( 'header_textcolor' )->section     = 'title_tagline';
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
				[
					'selector'        => '.site-title a',
					'render_callback' => function() {
						bloginfo( 'name' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => function() {
						bloginfo( 'description' );
					},
				]
			);
		}
	}
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'gridd_grid_header_branding_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'title_tagline',
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
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
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
		'section'           => 'title_tagline',
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
		'section'         => 'title_tagline',
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
		'section'   => 'title_tagline',
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
		'section'         => 'title_tagline',
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
		'section'         => 'title_tagline',
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
			'min'    => 0.5,
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
		'section'         => 'title_tagline',
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
		'section'         => 'title_tagline',
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
		'section'           => 'title_tagline',
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
		'section'           => 'title_tagline',
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
			'flex-start' => esc_html__( 'Start', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'End', 'gridd' ),
		],
		'sanitize_callback' => function( $value ) {
			return ( 'flex-start' !== $value && 'flex-end' !== $value && 'center' !== $value ) ? 'center' : $value;
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

// Add section.
gridd_add_customizer_outer_section(
	'gridd_grid_part_details_footer_copyright',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Copyright Area', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_footer_copyright',
			[
				'plus' => [
					esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
				],
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/footer/',
			]
		),
	]
);

gridd_add_customizer_field(
	[
		'type'              => 'editor',
		'settings'          => 'gridd_copyright_text',
		'label'             => esc_html__( 'Copyright Text', 'gridd' ),
		'description'       => esc_html__( 'The text for your copyright area (accepts HTML).', 'gridd' ),
		'section'           => 'gridd_grid_part_details_footer_copyright',
		/* translators: 1: CMS name, i.e. WordPress. 2: Theme name, 3: Theme author. */
		'default'           => sprintf( __( 'Proudly powered by %1$s | Theme: %2$s by %3$s.', 'gridd' ), '<a href="https://wordpress.org/">WordPress</a>', 'Gridd', '<a href="https://wplemon.com/" rel="nofollow">wplemon.com</a>' ),
		'transport'         => 'postMessage',
		'choices'           => [
			'language' => 'html',
		],
		'priority'          => 10,
		'sanitize_callback' => 'wp_kses_post',
		'partial_refresh'   => [
			'gridd_the_grid_part_footer_copyright_template' => [
				'selector'            => '.gridd-tp-footer_copyright',
				'container_inclusive' => false,
				'render_callback'     => function() {
					get_template_part( 'grid-parts/footer/template-footer-copyright' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_footer_copyright_bg_color',
		'label'     => esc_html__( 'Copyright area background-color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-copyright-bg',
		'priority'  => 110,
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'gridd-wcag-tc',
		'settings'  => 'gridd_grid_footer_copyright_color',
		'label'     => esc_html__( 'Copyright Text Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => '#000000',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-copyright-color',
		'priority'  => 120,
		'choices'   => [
			'setting' => 'gridd_grid_footer_copyright_bg_color',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'gridd-wcag-lc',
		'settings'  => 'gridd_grid_footer_copyright_links_color',
		'label'     => esc_html__( 'Copyright Links Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => '#0f5e97',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-copyright-links-color',
		'priority'  => 130,
		'choices'   => [
			'backgroundColor' => 'gridd_grid_footer_copyright_bg_color',
			'textColor'       => 'gridd_grid_footer_copyright_color',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_footer_copyright_text_font_size',
		'label'     => esc_html__( 'Font Size', 'gridd' ),
		'tooltip'   => esc_html__( 'The font-size defined here is relative to the body font-size so a size of 1em will be the same ssize as your content.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-footer-copyright-font-size', '$em' ],
		'priority'  => 140,
		'choices'   => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'radio-buttonset',
		'settings'  => 'gridd_grid_footer_copyright_text_align',
		'label'     => esc_html__( 'Text Alignment', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_copyright',
		'default'   => 'left',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-copyright-text-align',
		'priority'  => 150,
		'choices'   => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
	]
);

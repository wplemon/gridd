<?php
/**
 * Extra features.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

Customizer::add_section(
	'gridd_features',
	[
		'title'       => esc_attr__( 'Theme Features', 'gridd' ),
		'priority'    => 28,
		'description' => Customizer::section_description(
			'gridd_typography',
			[
				'plus' => [
					esc_html__( 'Fixed mode for featured images with custom height', 'gridd' ),
					esc_html__( 'Anchor links in headers ', 'gridd' ),
					esc_html__( 'Scroll to top button', 'gridd' ),
					esc_html__( 'Enable more custom widget-areas and customize their titles for easier identification', 'gridd' ),
				],
				'docs' => 'https://wplemon.com/documentation/gridd/theme-features-customizer-section/',
			]
		),
		'panel'       => 'gridd_options',
	]
);

Customizer::add_field(
	[
		'type'              => 'radio',
		'settings'          => 'gridd_featured_image_mode_archive',
		'label'             => esc_attr__( 'Featured Images Mode in Archives', 'gridd' ),
		'description'       => esc_html__( 'Select how featured images will be displayed in post-archives.', 'gridd' ),
		'section'           => 'gridd_features',
		'default'           => 'alignwide',
		'transport'         => 'refresh',
		'priority'          => 10,
		'choices'           => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
		],
		'active_callback'   => function() {
			return ( is_archive() || is_home() );
		},
		'sanitize_callback' => function( $value ) {
			return ( 'hidden' === $value || 'gridd-contain' === $value || 'alignwide' === $value ) ? $value : 'alignwide';
		},
	]
);

Customizer::add_field(
	[
		'type'              => 'radio',
		'settings'          => 'gridd_featured_image_mode_singular',
		'label'             => esc_attr__( 'Featured Images Mode in Single Posts', 'gridd' ),
		'description'       => esc_html__( 'Select how featured images will be displayed in single post-types (Applies to all post-types).', 'gridd' ),
		'section'           => 'gridd_features',
		'default'           => 'overlay',
		'transport'         => 'refresh',
		'priority'          => 20,
		'choices'           => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
			'alignfull'     => esc_attr__( 'Full Width', 'gridd' ),
			'overlay'       => esc_attr__( 'Overlay', 'gridd' ),
		],
		'active_callback'   => function() {
			return is_singular();
		},
		'sanitize_callback' => function( $value ) {
			return ( 'hidden' === $value || 'gridd-contain' === $value || 'alignwide' === $value || 'alignfull' === $value || 'overlay' === $value ) ? $value : 'overlay';
		},
	]
);

Customizer::add_field(
	[
		'type'            => 'dimension',
		'settings'        => 'gridd_featured_image_overlay_min_height',
		'label'           => esc_attr__( 'Overlay mode minimum height', 'gridd' ),
		'section'         => 'gridd_features',
		'default'         => 'overlay',
		'transport'       => 'postMessage',
		'priority'        => 20,
		'default'         => '87vh',
		'css_vars'        => '--gridd-image-header-min-height',
		'active_callback' => function() {
			return is_singular() && 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' );
		},
	]
);

Customizer::add_field(
	[
		'type'            => 'color',
		'settings'        => 'gridd_featured_image_overlay_background_color',
		'label'           => esc_attr__( 'Overlay Color', 'gridd' ),
		'section'         => 'gridd_features',
		'default'         => 'rgba(42,84,126,0.8)',
		'css_vars'        => '--gridd-image-header-overlay-color',
		'transport'       => 'postMessage',
		'priority'        => 20,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => function() {
			return is_singular() && 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' );
		},
	]
);

Customizer::add_field(
	[
		'type'              => 'gridd-wcag-tc',
		'settings'          => 'gridd_featured_image_overlay_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'description'       => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
		'section'           => 'gridd_features',
		'priority'          => 20,
		'default'           => '#fff',
		'css_vars'          => '--gridd-image-header-text-color',
		'transport'         => 'postMessage',
		'choices'           => [
			'setting' => 'gridd_featured_image_overlay_background_color',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return is_singular() && 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' );
		},
	]
);

Customizer::add_field(
	[
		'type'      => 'checkbox',
		'settings'  => 'gridd_show_next_prev',
		'label'     => esc_attr__( 'Show Next/Previous Post in single posts', 'gridd' ),
		'section'   => 'gridd_features',
		'default'   => true,
		'priority'  => 30,
		'transport' => 'refresh',
	]
);

Customizer::add_field(
	[
		'type'        => 'checkbox',
		'settings'    => 'gridd_archives_display_full_post',
		'label'       => esc_attr__( 'Show full post in archives', 'gridd' ),
		'description' => '',
		'section'     => 'gridd_features',
		'default'     => false,
		'priority'    => 40,
		'transport'   => 'refresh',
	]
);

$post_types = get_post_types(
	[
		'public'             => true,
		'publicly_queryable' => true,
	],
	'objects'
);

foreach ( $post_types as $post_type_id => $post_type_obj ) {
	Customizer::add_field(
		[
			'type'            => 'checkbox',
			'settings'        => "gridd_archive_display_grid_$post_type_id",
			'label'           => sprintf(
				/* translators: The post-type name. */
				esc_attr__( 'Display "%s" archives as a grid', 'gridd' ),
				$post_type_obj->labels->name
			),
			'section'         => 'gridd_features',
			'default'         => false,
			'transport'       => 'refresh',
			'priority'        => 50,
			'output'          => [
				[
					'element'       => ".gridd-post-type-archive-$post_type_id #main",
					'property'      => 'display',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => 'grid',
				],
				[
					'element'       => ".gridd-post-type-archive-$post_type_id #main > *",
					'property'      => 'height',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '100%',
				],
				[
					'element'       => ".gridd-post-type-archive-$post_type_id #main",
					'property'      => 'grid-gap',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '2em',
				],
				[
					'element'       => ".gridd-post-type-archive-$post_type_id #main",
					'property'      => 'grid-template-columns',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => 'repeat(auto-fit, minmax(20em, 1fr))',
				],
				[
					'element'       => ".gridd-post-type-archive-$post_type_id .posts-navigation",
					'property'      => 'grid-column-start',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '1',
				],
			],
			'active_callback' => function() use ( $post_type_id ) {
				return is_post_type_archive( $post_type_id ) || ( 'post' === $post_type_id && is_home() );
			},
		]
	);
}

Customizer::add_field(
	[
		'type'        => 'textarea',
		'settings'    => 'gridd_excerpt_more',
		'label'       => esc_attr__( 'Read More link', 'gridd' ),
		'description' => esc_html__( 'Available placeholder: %s for the post-title.', 'gridd' ), // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		'section'     => 'gridd_features',
		'priority'    => 60,
		/* translators: %s: Name of current post. Only visible to screen readers */
		'default'     => __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gridd' ),
		'transport'   => 'refresh',
	]
);

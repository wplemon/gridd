<?php
/**
 * Extra features.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\Rest;
use Gridd\Grid_Part\Sidebar;

$sanitization = new Sanitize();

Customizer::add_section(
	'gridd_features_global',
	[
		'title'    => esc_attr__( 'Global Settings', 'gridd' ),
		'priority' => 1,
		'panel'    => 'theme_settings',
	]
);

Customizer::add_section(
	'gridd_features_archive',
	[
		'title'    => esc_attr__( 'Post Archives Options', 'gridd' ),
		'priority' => 1,
		'panel'    => 'theme_settings',
	]
);

Customizer::add_section(
	'gridd_features_single_post',
	[
		'title'    => esc_attr__( 'Single Posts Options', 'gridd' ),
		'priority' => 1,
		'panel'    => 'theme_settings',
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'    => 'target_color_compliance',
		'label'       => esc_attr__( 'Color Accessibility Target Compliance', 'gridd' ),
		'description' => esc_html__( 'Select how text and link colors will be calculated.', 'gridd' ),
		'section'     => 'gridd_features_global',
		'default'     => 'auto',
		'transport'   => 'postMessage',
		'priority'    => 1,
		'choices'     => [
			'auto' => esc_html__( 'Auto', 'gridd' ) . '<div class="radio-tooltip">' . esc_html__( 'Select "Auto" mode to automatically get colors with maximum contrast to get as close to a higher accessibility standard as possible. It is similar to AAA compliance, with a fallback to above-AA colors is AAA is not achievable.', 'gridd' ) . '</div>',
			'AAA'  => 'AAA' . '<div class="radio-tooltip">' . esc_html__( 'Targeting for "AAA" compliance results in less intense but easier to read colors.', 'gridd' ) . '</div>',
			'AA'   => 'AA' . '<div class="radio-tooltip">' . esc_html__( 'Targeting for "AA" compliance will result in more vibrant colors while still maintaining a readable color contrast.', 'gridd' ) . '</div>',
		],
		'sanitize_callback' => function( $value ) {
			return ( 'auto' === $value || 'AAA' === $value || 'AA' === $value ) ? $value : 'auto';
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'same_linkcolor_hues',
		'label'       => esc_attr__( 'Consistent Hue', 'gridd' ),
		'description' => esc_html__( 'By default all your grid-parts will use the same link-color hue. Disable this option to access individual settings on some grid-parts.', 'gridd' ),
		'section'     => 'gridd_features_global',
		'default'     => true,
		'transport'   => 'postMessage',
		'priority'    => 1,
		'sanitize_callback' => function( $value ) {
			return (bool) $value;
		},
	]
);

/**
 * Options for post-archives.
 */
Customizer::add_field(
	[
		'type'              => 'radio-image',
		'settings'          => 'archive_post_mode',
		'label'             => esc_attr__( 'Posts Mode', 'gridd' ),
		'description'       => esc_html__( 'Please note that changes may not be visible if your posts don\'t have a featured image, and on mobiles they will fall-back to the default mode.', 'gridd' ),
		'section'           => 'gridd_features_archive',
		'default'           => 'default',
		'transport'         => 'refresh',
		'priority'          => 10,
		'choices'           => [
			'default' => get_template_directory_uri() . '/assets/images/archive-post-mode/default.svg',
			'card'    => get_template_directory_uri() . '/assets/images/archive-post-mode/card.svg',
		],
		'sanitize_callback' => function( $value ) {
			return ( 'default' === $value || 'card' === $value ) ? $value : 'default';
		},
	]
);

Customizer::add_field(
	[
		'type'              => 'select',
		'settings'          => 'gridd_featured_image_mode_archive',
		'label'             => esc_attr__( 'Featured Images Mode in Archives', 'gridd' ),
		'section'           => 'gridd_features_archive',
		'default'           => 'alignwide',
		'transport'         => 'refresh',
		'priority'          => 20,
		'choices'           => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
		],
		'active_callback'   => [
			[
				'setting'  => 'archive_post_mode',
				'value'    => 'default',
				'operator' => '===',
			],
		],
		'sanitize_callback' => function( $value ) {
			return ( 'hidden' === $value || 'gridd-contain' === $value || 'alignwide' === $value ) ? $value : 'alignwide';
		},
	]
);

Customizer::add_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_archives_display_full_post',
		'label'           => esc_attr__( 'Show full post in archives', 'gridd' ),
		'description'     => '',
		'section'         => 'gridd_features_archive',
		'default'         => false,
		'priority'        => 30,
		'transport'       => 'refresh',
		'active_callback' => [
			[
				'setting'  => 'archive_post_mode',
				'value'    => 'default',
				'operator' => '===',
			],
		],
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
				esc_html__( 'Display "%s" archives as a grid', 'gridd' ),
				$post_type_obj->labels->name
			),
			'section'         => 'gridd_features_archive',
			'default'         => false,
			'transport'       => 'refresh',
			'priority'        => 40,
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
		]
	);
}

Customizer::add_field(
	[
		'type'        => 'code',
		'settings'    => 'excerpt_read_more',
		'label'       => esc_attr__( 'Read More link', 'gridd' ),
		'description' => esc_html__( 'If you want to include the post title in your read-more link, you can use "%s" (without the quotes) and it will be replaced with the post\'s title.', 'gridd' ), // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
		'section'     => 'gridd_features_archive',
		'priority'    => 50,
		/* translators: %s: Name of current post. Only visible to screen readers */
		'default'     => __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gridd' ),
		'transport'   => 'refresh',
		'choices'     => [
			'language' => 'html',
		],
	]
);

Customizer::add_field(
	[
		'type'            => 'slider',
		'settings'        => 'archive_card_image_width',
		'label'           => esc_attr__( 'Image Width', 'gridd' ),
		'description'     => esc_html__( 'Width of the featured image in relation to the global width (percentage).', 'gridd' ),
		'section'         => 'gridd_features_archive',
		'default'         => 50,
		'transport'       => 'auto',
		'priority'        => 30,
		'choices'         => [
			'min'    => 20,
			'max'    => 80,
			'step'   => 1,
			'suffix' => '%',
		],
		'output'          => [
			[
				'element'       => '.gridd-post-mode-card',
				'property'      => '--a-crd-img-w',
				'value_pattern' => '$%',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'archive_post_mode',
				'value'    => 'card',
				'operator' => '===',
			],
		],
	]
);

/**
 * Options for single posts.
 */
Customizer::add_field(
	[
		'type'              => 'select',
		'settings'          => 'gridd_featured_image_mode_singular',
		'label'             => esc_attr__( 'Featured Images Mode in Single Posts', 'gridd' ),
		'section'           => 'gridd_features_single_post',
		'default'           => 'overlay',
		'transport'         => 'refresh',
		'priority'          => 10,
		'choices'           => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
			'alignfull'     => esc_attr__( 'Full Width', 'gridd' ),
			'overlay'       => esc_attr__( 'Overlay', 'gridd' ),
		],
		/**
		 * WIP
		'active_callback'   => function() {
			return is_singular();
		},
		*/
		'sanitize_callback' => function( $value ) {
			return ( 'hidden' === $value || 'gridd-contain' === $value || 'alignwide' === $value || 'alignfull' === $value || 'overlay' === $value ) ? $value : 'overlay';
		},
	]
);

Customizer::add_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_featured_image_overlay_color_from_image',
		'label'           => esc_html__( 'Use Image Colors', 'gridd' ),
		'section'         => 'gridd_features_single_post',
		'default'         => true,
		'transport'       => 'refresh',
		'priority'        => 20,
		'active_callback' => function() {
			return 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' ) && function_exists( 'jetpack_require_lib' );
		},
	]
);

Customizer::add_field(
	[
		'type'            => 'dimension',
		'settings'        => 'gridd_featured_image_overlay_min_height',
		'label'           => esc_attr__( 'Featured image minimum height', 'gridd' ),
		'section'         => 'gridd_features_single_post',
		'default'         => 'overlay',
		'transport'       => 'postMessage',
		'priority'        => 30,
		'default'         => '87vh',
		'output'          => [
			[
				'element'  => ':root',
				'property' => '--im-hmh',
			],
		],
		'active_callback' => function() {
			return 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' );
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'gridd_featured_image_overlay_background_color',
		'label'           => esc_attr__( 'Overlay Color', 'gridd' ),
		'section'         => 'gridd_features_single_post',
		'default'         => 'rgba(42,84,126,0.8)',
		'output'          => [
			[
				'element'  => ':root',
				'property' => '--im-hoc',
			],
		],
		'transport'       => 'postMessage',
		'priority'        => 40,
		'choices'         => [
			'formComponent' => 'ChromePicker',
		],
		'active_callback' => function() {
			return 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' ) && ( ! get_theme_mod( 'gridd_featured_image_overlay_color_from_image', true ) || ! function_exists( 'jetpack_require_lib' ) );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'gridd_featured_image_overlay_text_color',
		'label'             => esc_html__( 'Feature Image Overlay Text Color', 'gridd' ),
		'section'           => 'gridd_features_single_post',
		'priority'          => 50,
		'default'           => '#fff',
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--im-htc',
			],
		],
		'transport'         => 'postMessage',
		'choices'           => [
			'backgroundColor' => 'gridd_featured_image_overlay_background_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return 'overlay' === get_theme_mod( 'gridd_featured_image_mode_singular', 'overlay' ) && ( ! get_theme_mod( 'gridd_featured_image_overlay_color_from_image', true ) || ! function_exists( 'jetpack_require_lib' ) );
		},
	]
);

Customizer::add_field(
	[
		'type'      => 'checkbox',
		'settings'  => 'gridd_show_next_prev',
		'label'     => esc_attr__( 'Show Next/Previous Post in single posts', 'gridd' ),
		'section'   => 'gridd_features_single_post',
		'default'   => true,
		'priority'  => 60,
		'transport' => 'refresh',
	]
);

add_action(
	'init',
	function() {
		Customizer::add_field(
			[
				'type'        => 'multicheck',
				'settings'    => 'gridd_rest_api_partials',
				'label'       => esc_attr__( 'Deferred Parts', 'gridd' ),
				'description' => Customizer::get_control_description(
					[
						'short'   => '',
						'details' => esc_html__( 'Select the parts that should be loaded after the initial request. Non-essential parts can be added here. This can speed-up the initial page-load and users on slower connections can start consuming your content faster.', 'gridd' ),
					]
				),
				'section'     => 'gridd_features_global',
				'priority'    => 70,
				'multiple'    => 999,
				'choices'     => Rest::get_all_partials(),
				'default'     => [],
				'transport'   => 'postMessage',
			]
		);
	}
);

/**
 * Disabled control.
 *
Customizer::add_field(
	[
		'type'        => 'checkbox',
		'settings'    => 'disable_editor_styles',
		'label'       => esc_html__( 'Disable Editor Styles', 'gridd' ),
		'description' => esc_html__( 'Enable this option to prevent the theme from styling the posts editor to match your options, and instead uses the default WordPress styles for the editor.', 'gridd' ),
		'section'     => 'gridd_features_global',
		'default'     => false,
		'transport'   => 'postMessage',
		'priority'    => 999,
	]
);
*/

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

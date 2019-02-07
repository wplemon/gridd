<?php
/**
 * Extra features.
 *
 * @package Gridd
 * @since 1.0
 */

gridd_add_customizer_section(
	'gridd_features',
	[
		'title'       => esc_attr__( 'Theme Features', 'gridd' ),
		'priority'    => 28,
		'description' => '<a href="https://wplemon.com/documentation/gridd/theme-features-customizer-section/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ),
		'panel'       => 'gridd_options',
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'radio',
		'settings'        => 'gridd_featured_image_mode_archive',
		'label'           => esc_attr__( 'Featured Images Mode in Archives', 'gridd' ),
		'description'     => esc_html__( 'Select how featured images will be displayed in post-archives.', 'gridd' ),
		'section'         => 'gridd_features',
		'default'         => 'alignwide',
		'transport'       => 'refresh',
		'choices'         => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
		],
		'active_callback' => function() {
			return ( is_archive() || is_home() );
		},
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'radio',
		'settings'        => 'gridd_featured_image_mode_singular',
		'label'           => esc_attr__( 'Featured Images Mode in Single Posts', 'gridd' ),
		'description'     => esc_html__( 'Select how featured images will be displayed in single post-types (Applies to all post-types).', 'gridd' ),
		'section'         => 'gridd_features',
		'default'         => 'alignwide',
		'transport'       => 'refresh',
		'choices'         => [
			'hidden'        => esc_attr__( 'Hidden', 'gridd' ),
			'gridd-contain' => esc_attr__( 'Normal', 'gridd' ),
			'alignwide'     => esc_attr__( 'Wide', 'gridd' ),
			'alignfull'     => esc_attr__( 'Full Width', 'gridd' ),
		],
		'active_callback' => function() {
			return is_singular();
		},
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'checkbox',
		'settings'  => 'gridd_show_next_prev',
		'label'     => esc_attr__( 'Show Next/Previous Post in single posts', 'gridd' ),
		'section'   => 'gridd_features',
		'default'   => true,
		'transport' => 'refresh',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'checkbox',
		'settings'    => 'gridd_archives_display_full_post',
		'label'       => esc_attr__( 'Show full post in archives', 'gridd' ),
		'description' => '',
		'section'     => 'gridd_features',
		'default'     => false,
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

foreach ( $post_types as $id => $post_type ) {
	gridd_add_customizer_field(
		[
			'type'            => 'checkbox',
			'settings'        => "gridd_archive_display_grid_$id",
			'label'           => sprintf(
				/* translators: The post-type name. */
				esc_attr__( 'Display "%s" archives as a grid', 'gridd' ),
				$post_type->labels->name
			),
			'section'         => 'gridd_features',
			'default'         => false,
			'transport'       => 'refresh',
			'output'          => [
				[
					'element'       => ".gridd-post-type-archive-$id #main",
					'property'      => 'display',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => 'grid',
				],
				[
					'element'       => ".gridd-post-type-archive-$id #main > *",
					'property'      => 'height',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '100%',
				],
				[
					'element'       => ".gridd-post-type-archive-$id #main",
					'property'      => 'grid-gap',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '2em',
				],
				[
					'element'       => ".gridd-post-type-archive-$id #main",
					'property'      => 'grid-template-columns',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => 'repeat(auto-fit, minmax(20em, 1fr))',
				],
				[
					'element'       => ".gridd-post-type-archive-$id .posts-navigation",
					'property'      => 'grid-column-start',
					'exclude'       => [ false, 0, 'false', '0' ],
					'value_pattern' => '1',
				],
			],
			'active_callback' => function() use ( $id ) {
				return is_post_type_archive( $id ) || ( 'post' === $id && is_home() );
			},
		]
	);
}

gridd_add_customizer_field(
	[
		'type'        => 'textarea',
		'settings'    => 'gridd_excerpt_more',
		'label'       => esc_attr__( 'Read More link', 'gridd' ),
		'description' => esc_html__( 'Available placeholder: %s for the post-title.', 'gridd' ), // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	'section'         => 'gridd_features',
	/* translators: %s: Name of current post. Only visible to screen readers */
	'default'         => __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'gridd' ),
	'transport'       => 'refresh',
	]
);

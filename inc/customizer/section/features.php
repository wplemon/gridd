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
		'type'        => 'checkbox',
		'settings'    => 'gridd_show_next_prev',
		'label'       => esc_attr__( 'Show Next/Previous Post in single posts', 'gridd' ),
		'section'     => 'gridd_features',
		'default'     => true,
		'transport'   => 'refresh',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'radio',
		'settings'    => 'gridd_archive_grid_template_columns',
		'label'       => esc_attr__( 'Grid Columns in Post Archives', 'gridd' ),
		'section'     => 'gridd_features',
		'default'     => 'auto',
        'transport'   => 'auto',
        'choices'     => [
            'single'                              => esc_html__( 'Single Column', 'gridd' ),
            'repeat(auto-fit, minmax(20em, 1fr))' => esc_html__( 'Grid - Auto-fit', 'gridd' ),
        ],
        'output'      => [
            [
                'element'       => [ '.archive #main', '.blog #main' ],
                'property'      => 'display',
                'exclude'       => 'repeat(auto-fit, minmax(20em, 1fr))',
                'value_pattern' => 'grid',
            ],
            [
                'element'       => [ '.archive #main > article', '.blog #main > article' ],
                'property'      => 'height',
                'exclude'       => 'repeat(auto-fit, minmax(20em, 1fr))',
                'value_pattern' => '100%',
            ],
            [
                'element'       => [ '.archive #main', '.blog #main' ],
                'property'      => 'grid-gap',
                'exclude'       => 'repeat(auto-fit, minmax(20em, 1fr))',
                'value_pattern' => '1em',
            ],
            [
                'element'       => [ '.archive #main', '.blog #main' ],
                'property'      => 'grid-template-columns',
            ]
        ]
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'radio',
		'settings'    => 'gridd_archive_mode',
		'label'       => esc_attr__( 'Post display mode in archives', 'gridd' ),
		'description' => '',
		'section'     => 'gridd_features',
		'default'     => 'excerpt',
		'transport'   => 'refresh',
		'choices'     => [
			'excerpt' => esc_html__( 'Excerpt', 'gridd' ),
			'full'    => esc_attr__( 'Full Post', 'gridd' ),
		],
	]
);

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

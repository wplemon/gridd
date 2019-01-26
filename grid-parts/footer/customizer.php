<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Grid_Part\Footer;

gridd_add_customizer_section(
	'gridd_grid_part_details_footer',
	[
		'title'       => esc_html__( 'Footer', 'gridd' ),
		'description' => sprintf(
			'<div class="gridd-section-description">%1$s%2$s</div>',
			( ! Gridd::is_pro() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for a separate grid for mobile devices.', 'gridd' ) . '</div>' : '',
			'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/footer/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
		),
		'priority'    => 25,
		'panel'       => 'gridd_options',
	]
);

$footer_grid_parts = [
	[
		'label'    => esc_html__( 'Copyright Area', 'gridd' ),
		'color'    => '#DC3232',
		'priority' => 100,
		'hidden'   => false,
		'id'       => 'footer_copyright',
	],
	[
		'label'    => esc_html__( 'Social Media', 'gridd' ),
		'color'    => '#0996c3',
		'priority' => 200,
		'hidden'   => false,
		'id'       => 'footer_social_media',
	],
];

$sidebars_nr = Footer::get_number_of_sidebars();
for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
	$footer_grid_parts[] = [
		/* translators: The widget-area number. */
		'label'    => sprintf( esc_html__( 'Footer Widget Area %d', 'gridd' ), absint( $i ) ),
		'color'    => '#000000',
		'priority' => 8 + $i * 2,
		'hidden'   => false,
		'class'    => "footer_sidebar_$i",
		'id'       => "footer_sidebar_$i",
	];
}

gridd_add_customizer_field(
	[
		'settings'          => 'gridd_footer_grid',
		'section'           => 'gridd_grid_part_details_footer',
		'type'              => 'gridd_grid',
		'grid-part'         => 'footer',
		'label'             => esc_html__( 'Grid Settings', 'gridd' ),
		'description'       => __( 'Edit settings for the grid. For more information and documentation on how the grid works, please read <a href="https://wplemon.com/documentation/gridd/the-grid-control/" target="_blank">this article</a>.', 'gridd' ),
		'default'           => Footer::get_grid_defaults(),
		'choices'           => [
			'parts' => $footer_grid_parts,
		],
		'sanitize_callback' => [ gridd()->customizer, 'sanitize_gridd_grid' ],
		'transport'         => 'postMessage',
		'partial_refresh'   => [
			'gridd_footer_grid_template' => [
				'selector'            => '.gridd-tp-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'footer' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_grid_gap',
		'label'       => esc_attr__( 'Grid Gap', 'gridd' ),
		'description' => gridd()->customizer->get_text( 'grid-gap-description' ),
		'tooltip'     => esc_html__( 'If you have a background-color or background-image defined for your footer, then these will be visible through these gaps which creates a unique appearance since each grid-part looks separate.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '0',
		'css_vars'    => '--gridd-footer-grid-gap',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_max_width',
		'label'       => esc_attr__( 'Max-Width', 'gridd' ),
		'description' => gridd()->customizer->get_text( 'grid-part-max-width' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-max-width',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_footer_padding',
		'label'       => esc_attr__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Inner padding for all parts in the footer.', 'gridd' ),
		'tooltip'     => gridd()->customizer->get_text( 'padding' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '1em',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-padding',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_footer_background_color',
		'label'       => esc_attr__( 'Background Color', 'gridd' ),
		'description' => esc_html__( 'Choose a background color for the footer.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => '#ffffff',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-bg',
		'choices'     => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'slider',
		'settings'  => 'gridd_grid_footer_border_top_width',
		'label'     => esc_attr__( 'Border-Top Width', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer',
		'default'   => 1,
		'transport' => 'postMessage',
		'css_vars'  => [ '--gridd-footer-border-top-width', '$px' ],
		'priority'  => 50,
		'choices'   => [
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'px',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_footer_border_top_color',
		'label'       => esc_attr__( 'Border-top Color', 'gridd' ),
		'description' => esc_html__( 'Choose a color for the top border.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer',
		'default'     => 'rgba(0,0,0,.1)',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-border-top-color',
		'choices'     => [
			'alpha' => true,
		],
	]
);

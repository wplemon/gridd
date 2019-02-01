<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Grid_Parts;
use Gridd\Customizer;

gridd_add_customizer_section(
	'gridd_grid_part_details_header',
	[
		'title'       => esc_html__( 'Header', 'gridd' ),
		'description' => Customizer::section_description(
			[
				'plus' => [
					esc_html__( 'Separate grid for mobile devices', 'gridd' ),
				],
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/header/',
			]
		),
		'priority'    => 24,
		'panel'       => 'gridd_options',
	]
);

$header_grid_parts = Grid_Parts::get_instance()->get_parts();

// Remove parts that are not valid in this sub-grid.
$parts_to_remove = [ 'content', 'header', 'footer', 'nav-handheld', 'nested-grid-1', 'nested-grid-2', 'nested-grid-3', 'nested-grid-4' ];
foreach ( $header_grid_parts as $key => $part ) {
	if ( isset( $part['id'] ) && in_array( $part['id'], $parts_to_remove, true ) ) {
		unset( $header_grid_parts[ $key ] );
	}
}

/**
 * Remove header-textcolor control.
 * We have separate controls for title & subtitle so this one is not necessary.
 *
 * @since 1.0
 * @param object $wp_customize The WordPress Customizer instance.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {
		$wp_customize->remove_control( 'header_textcolor' );
	}
);

$header_grid_parts[] = [
	'label'    => esc_html__( 'Branding', 'gridd' ),
	'color'    => [ '#FFEB3B', '#000' ],
	'priority' => 0,
	'hidden'   => false,
	'id'       => 'header_branding',
];

$header_grid_parts[] = [
	'label'    => esc_html__( 'Search', 'gridd' ),
	'color'    => [ '#CFD8DC', '#000' ],
	'priority' => 200,
	'hidden'   => false,
	'id'       => 'header_search',
];

$header_grid_parts[] = [
	'label'    => esc_html__( 'Contact Information', 'gridd' ),
	'color'    => [ '#D4E157', '#000' ],
	'priority' => 1000,
	'hidden'   => false,
	'id'       => 'header_contact_info',
];

$header_grid_parts[] = [
	'label'    => esc_html__( 'Social Media', 'gridd' ),
	'color'    => [ '#009688', '#fff' ],
	'priority' => 2000,
	'hidden'   => false,
	'id'       => 'social_media',
];

gridd_add_customizer_field(
	[
		'settings'          => 'gridd_header_grid',
		'section'           => 'gridd_grid_part_details_header',
		'type'              => 'gridd_grid',
		'grid-part'         => 'header',
		'label'             => esc_html__( 'Grid Settings', 'gridd' ),
		'description'       => __( 'Edit settings for your footer grid. For more information and documentation on how the grid works, please read <a href="https://wplemon.com/documentation/gridd/the-grid-control/" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'default'           => Header::get_grid_defaults(),
		'choices'           => [
			'parts' => $header_grid_parts,
		],
		'sanitize_callback' => [ gridd()->customizer, 'sanitize_gridd_grid' ],
		'transport'         => 'postMessage',
		'partial_refresh'   => [
			'gridd_header_grid_part_renderer' => [
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
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_header_max_width',
		'label'       => esc_html__( 'Max-Width', 'gridd' ),
		'description' => esc_html__( 'The maximum width that the contents of this grid-part can use.', 'gridd' ),
		'description' => __( 'Use any valid CSS value like <code>50em</code>, <code>800px</code> or <code>100%</code>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => '',
		'css_vars'    => '--gridd-header-max-width',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_header_grid_gap',
		'label'       => esc_html__( 'Grid Gap', 'gridd' ),
		'description' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'tooltip'     => esc_html__( 'If you have a background-color or background-image defined for your header, then these will be visible through these gaps which creates a unique appearance since each grid-part looks separate.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => '0',
		'css_vars'    => '--gridd-header-grid-gap',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_header_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'tooltip'   => esc_html__( 'Choose a background color for the header. Individual grid-parts can override this by setting their own background color for their area. If you are using a grid-gap the color defined here will be visible between grid-parts.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-bg',
		'choices'   => [
			'alpha' => true,
		],
		'priority'  => 70,
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'radio',
		'settings'  => 'gridd_grid_header_box_shadow',
		'label'     => esc_html__( 'Drop Shadow Intensity', 'gridd' ),
		'tooltip'   => esc_html__( 'Set to "None" if you want to disable the shadow for this grid-part, or increase the intensity for a more dramatic effect.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header',
		'default'   => '0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-header-box-shadow',
		'priority'  => 200,
		'choices'   => [
			'none' => esc_html__( 'None', 'gridd' ),
			'0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)' => esc_html__( 'Extra Light', 'gridd' ),
			'0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)' => esc_html__( 'Light', 'gridd' ),
			'0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)' => esc_html__( 'Medium', 'gridd' ),
			'0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)' => esc_html__( 'Heavy', 'gridd' ),
			'0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22)' => esc_html__( 'Extra Heavy', 'gridd' ),
		],
		'priority'  => 90,
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'toggle',
		'settings'  => 'gridd_header_sticky',
		'label'     => esc_html__( 'Sticky on Large Devices', 'gridd' ),
		'tooltip'   => esc_html__( 'Enable to stick this area to the top of the page when users scroll-down on devices larger than the breakpoint you defined in your main grid.', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => 300,
		'priority'  => 91,
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'toggle',
		'settings'        => 'gridd_header_sticky_mobile',
		'label'           => esc_html__( 'Sticky on Small Devices', 'gridd' ),
		'tooltip'         => esc_html__( 'Enable to stick this area to the top of the page when users scroll-down on devices smaller than the breakpoint you defined in your main grid.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header',
		'default'         => false,
		'transport'       => 'refresh',
		'priority'        => 300,
		'active_callback' => [
			[
				'setting'  => 'gridd_header_sticky',
				'operator' => '===',
				'value'    => true,
			],
		],
		'priority'        => 91,
	]
);

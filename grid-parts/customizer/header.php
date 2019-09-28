<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Grid_Parts;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

/**
 * Move the header-image control.
 *
 * @since 1.0
 * @param WP_Customize The WordPress Customizer main object.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {
		$wp_customize->get_control( 'header_image' )->section  = 'gridd_grid_part_details_header';
		$wp_customize->get_control( 'header_image' )->priority = 80;
	}
);

new \Kirki\Section(
	'gridd_grid_part_details_header',
	[
		'title'    => esc_html__( 'Header', 'gridd' ),
		'priority' => 24,
		'panel'    => 'gridd_options',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_header_grid',
		'section'           => 'gridd_grid_part_details_header',
		'type'              => 'gridd_grid',
		'grid-part'         => 'header',
		'label'             => esc_html__( 'Header Grid', 'gridd' ),
		'description'       => Customizer::get_control_description(
			[
				'short'   => '',
				'details' => __( 'You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
			]
		),
		'default'           => Header::get_grid_defaults(),
		'choices'           => [
			'parts' => Header::get_header_grid_parts(),
		],
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'priority'          => 10,
		/**
		 * Deactivated the partial-refresh due to https://github.com/wplemon/gridd/issues/89
		 *
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
		*/
	]
);

new \Kirki\Field\Dimension(
	[
		'type'      => 'dimension',
		'settings'  => 'gridd_grid_header_max_width',
		'label'     => esc_html__( 'Header Maximum Width', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header',
		'default'   => '45em',
		'priority'  => 20,
		'css_vars'  => '--h-mw',
		'transport' => 'postMessage',
	]
);

new \Kirki\Field\Dimension(
	[
		'type'      => 'dimension',
		'settings'  => 'gridd_grid_header_padding',
		'label'     => esc_html__( 'Header Padding', 'gridd' ),
		'section'   => 'gridd_grid_part_details_header',
		'default'   => '0',
		'priority'  => 20,
		'css_vars'  => '--h-pd',
		'transport' => 'postMessage',
	]
);

new \Kirki\Field\Dimension(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_header_grid_gap',
		'label'       => esc_html__( 'Grid Gap', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. If you have a background-color or background-image defined for your header, then these will be visible through these gaps which creates a unique appearance since each grid-part looks separate. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => '0',
		'priority'    => 30,
		'css_vars'    => '--h-gg',
		'transport'   => 'postMessage',
	]
);

new \Kirki\Field\Color(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_part_details_header_background_color',
		'label'       => esc_html__( 'Background Color', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => esc_html__( 'Choose a background color for the header. Individual grid-parts can override this by setting their own background color for their area. If you are using a grid-gap the color defined here will be visible between grid-parts.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => '#ffffff',
		'transport'   => 'postMessage',
		'priority'    => 40,
		'css_vars'    => '--h-bg',
		'choices'     => [
			'alpha' => true,
		],
		'priority'    => 70,
	]
);

Customizer::add_field(
	[
		'type'        => 'checkbox',
		'settings'    => 'gridd_grid_part_details_header_parts_background_override',
		'label'       => esc_html__( 'Override Header Parts Background', 'gridd' ),
		'description' => esc_html__( 'Enable this option to force-override the background color of all grid-parts in your header.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => false,
		'priority'    => 82,
	]
);

Customizer::add_field(
	[
		'type'              => 'select',
		'settings'          => 'gridd_grid_header_box_shadow',
		'label'             => esc_html__( 'Drop Shadow Intensity', 'gridd' ),
		'description'       => esc_html__( 'Set to "None" to disable the shadow, or increase the intensity for a more dramatic effect.', 'gridd' ),
		'section'           => 'gridd_grid_part_details_header',
		'default'           => '0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)',
		'transport'         => 'postMessage',
		'css_vars'          => '--h-bs',
		'priority'          => 50,
		'choices'           => [
			'none' => esc_html__( 'None', 'gridd' ),
			'0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)' => esc_html__( 'Extra Light', 'gridd' ),
			'0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)' => esc_html__( 'Light', 'gridd' ),
			'0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)' => esc_html__( 'Medium', 'gridd' ),
			'0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)' => esc_html__( 'Heavy', 'gridd' ),
			'0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22)' => esc_html__( 'Extra Heavy', 'gridd' ),
		],
		'sanitize_callback' => 'sanitize_text_field',
	]
);

Customizer::add_field(
	[
		'type'        => 'toggle',
		'settings'    => 'gridd_header_sticky',
		'label'       => esc_html__( 'Sticky on Large Devices', 'gridd' ),
		'description' => esc_html__( 'Enable to stick this area to the top of the page when users scroll-down on devices larger than the breakpoint you defined in your main grid.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_header',
		'default'     => false,
		'transport'   => 'refresh',
		'priority'    => 60,
	]
);

Customizer::add_field(
	[
		'type'            => 'toggle',
		'settings'        => 'gridd_header_sticky_mobile',
		'label'           => esc_html__( 'Sticky on Small Devices', 'gridd' ),
		'description'     => esc_html__( 'Enable to stick this area to the top of the page when users scroll-down on devices smaller than the breakpoint you defined in your main grid.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_header',
		'default'         => false,
		'transport'       => 'refresh',
		'priority'        => 61,
		'active_callback' => [
			[
				'setting'  => 'gridd_header_sticky',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

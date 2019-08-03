<?php
/**
 * Customizer Grid Options.
 *
 * @package Gridd
 */

use Gridd\Grid;
use Gridd\Grid_Parts;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();
$grid_parts   = Grid_Parts::get_instance()->get_parts();

/**
 * Move the background controls to the grid section.
 *
 * @since 1.0
 * @param WP_Customize The WordPress Customizer main object.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {

		// Move the background-color control.
		$wp_customize->get_control( 'background_color' )->section     = 'gridd_grid';
		$wp_customize->get_control( 'background_color' )->priority    = 90;
		$wp_customize->get_control( 'background_color' )->description = Customizer::get_control_description(
			[
				esc_html__( 'Background will only be seen when grid-parts have a transparent background color, or if the site grid is not set to 100% width.', 'gridd' ),
			]
		);

		// Move the background-image control.
		$wp_customize->get_control( 'background_image' )->section       = 'gridd_grid';
		$wp_customize->get_control( 'background_image' )->priority      = 90;
		$wp_customize->get_control( 'background_image' )->description   = Customizer::get_control_description(
			[
				esc_html__( 'Background will only be seen when grid-parts have a transparent background color, or if the site grid is not set to 100% width.', 'gridd' ),
			]
		);
		$wp_customize->get_control( 'background_preset' )->section      = 'gridd_grid';
		$wp_customize->get_control( 'background_preset' )->priority     = 90;
		$wp_customize->get_control( 'background_position' )->section    = 'gridd_grid';
		$wp_customize->get_control( 'background_position' )->priority   = 90;
		$wp_customize->get_control( 'background_size' )->section        = 'gridd_grid';
		$wp_customize->get_control( 'background_size' )->priority       = 90;
		$wp_customize->get_control( 'background_repeat' )->section      = 'gridd_grid';
		$wp_customize->get_control( 'background_repeat' )->priority     = 90;
		$wp_customize->get_control( 'background_attachment' )->section  = 'gridd_grid';
		$wp_customize->get_control( 'background_attachment' )->priority = 90;
	}
);

Customizer::add_section(
	'gridd_grid',
	[
		'title'       => esc_html__( 'Site Grid', 'gridd' ),
		'priority'    => 22,
		'description' => Customizer::section_description(
			'gridd_grid',
			[
				'docs' => 'https://wplemon.github.io/gridd/customizer-sections/grid.html',
				'tip'  => sprintf(
					/* translators: Link to the blocks-management screen. */
					__( 'Did you know you can place reusable blocks anywhere in your grid? <a href="%s" target="_blank">Click here</a> to manage your reusable blocks.', 'gridd' ),
					esc_url( admin_url( 'edit.php?post_type=wp_block' ) )
				),
			]
		),
		'panel'       => 'gridd_options',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_grid',
		'section'           => 'gridd_grid',
		'type'              => 'gridd_grid',
		'grid-part'         => false,
		'label'             => esc_html__( 'Global Site Grid', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => __( 'The settings in this control apply to all your pages. You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
			]
		),
		'default'           => Grid::get_grid_default_value(),
		'priority'          => 10,
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'choices'           => [
			'parts'     => Grid_Parts::get_instance()->get_parts(),
			'duplicate' => 'gridd_grid_mobile',
		],
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_gap',
		'label'       => esc_html__( 'Grid Container Gap', 'gridd' ),
		'description' => Customizer::get_control_description(
			[
				'details' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			]
		),
		'section'     => 'gridd_grid',
		'default'     => '0',
		'transport'   => 'auto',
		'priority'    => 30,
		'output'      => [
			[
				'element'  => '.gridd-site-wrapper',
				'property' => 'grid-gap',
			],
		],
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_max_width',
		'label'       => esc_html__( 'Site Maximum Width', 'gridd' ),
		'description' => esc_html__( 'Set the value to something other than 100% to use a boxed layout.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '',
		'priority'    => 40,
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-grid-max-width',
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

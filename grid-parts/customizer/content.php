<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

Customizer::add_outer_section(
	'gridd_grid_part_details_content',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Content', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_content',
			[
				'docs' => 'https://wplemon.github.io/gridd/grid-parts/content.html',
			]
		),
	]
);

Customizer::add_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_content_max_width',
		'label'       => esc_html__( 'Max-Width', 'gridd' ),
		'description' => esc_html__( 'The maximum width that the contents of this grid-part can use.', 'gridd' ),
		'description' => __( 'Use any valid CSS value like <code>50em</code>, <code>800px</code> or <code>100%</code>.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_content',
		'default'     => '45em',
		'css_vars'    => '--gridd-content-max-width',
		'transport'   => 'postMessage',
		'priority'    => 10,
	]
);

Customizer::add_field(
	[
		'type'      => 'dimensions',
		'settings'  => 'gridd_grid_content_padding',
		'label'     => esc_html__( 'Container Padding', 'gridd' ),
		'section'   => 'gridd_grid_part_details_content',
		'default'   => [
			'top'    => '0px',
			'bottom' => '0px',
			'left'   => '20px',
			'right'  => '20px',
		],
		'css_vars'  => [
			[ '--gridd-content-padding-top', '$', 'top' ],
			[ '--gridd-content-padding-bottom', '$', 'bottom' ],
			[ '--gridd-content-padding-left', '$', 'left' ],
			[ '--gridd-content-padding-right', '$', 'right' ],
		],
		'transport' => 'postMessage',
		'priority'  => 15,
	]
);

Customizer::add_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_content_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'tooltip'   => esc_html__( 'Always prefer light backgrounds with dark text for increased accessibility', 'gridd' ),
		'section'   => 'gridd_grid_part_details_content',
		'default'   => '#ffffff',
		'css_vars'  => '--gridd-content-bg',
		'transport' => 'postMessage',
		'priority'  => 30,
		'choices'   => [
			'alpha' => true,
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

Customizer::add_outer_section(
	'gridd_grid_part_details_layer-slider',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Layer Slider', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_layer-slider',
			[
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/layer-slider/',
			]
		),
	]
);

$sliders       = \LS_Sliders::find();
$sliders_array = [
	'' => esc_html__( 'Choose a slider', 'gridd' ),
];
foreach ( $sliders as $slider ) {
	$sliders_array[ $slider['id'] ] = $slider['name'];
}
Customizer::add_field(
	[
		'type'              => 'select',
		'settings'          => 'gridd_grid_layerslider_slider',
		'label'             => esc_html__( 'Choose Slider', 'gridd' ),
		'section'           => 'gridd_grid_part_details_layer-slider',
		'default'           => '',
		'priority'          => 10,
		'transport'         => 'refresh',
		'choices'           => $sliders_array,
		'transport'         => 'postMessage',
		'partial_refresh'   => [
			'gridd_grid_layerslider_slider_template' => [
				'selector'            => '.gridd-tp-layer-slider',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'layer-slider' );
				},
			],
		],
		'sanitize_callback' => function( $value ) use ( $sliders_array ) {
			if ( ! isset( $sliders_array[ $value ] ) ) {
				return '';
			}
			return $value;
		},
	]
);

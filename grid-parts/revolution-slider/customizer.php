<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;

gridd_add_customizer_section(
	'gridd_grid_part_details_revolution-slider',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_html__( '%s Options', 'gridd' ), esc_html__( 'Revolution Slider', 'gridd' ) ),
		'description' => Customizer::section_description(
			'gridd_grid_part_details_revolution-slider',
			[
				'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/revolution-slider/',
			]
		),
		'section'     => 'gridd_grid',
	]
);

$slider        = new \RevSliderSlider();
$sliders_array = [
	'' => esc_html__( 'Select Slider', 'gridd' ),
];
foreach ( $slider->getArrSliders() as $slide ) {
	$sliders_array[ $slide->getAlias() ] = $slide->getTitle();
}

gridd_add_customizer_field(
	[
		'type'            => 'select',
		'settings'        => 'gridd_grid_revslider_slider',
		'label'           => esc_html__( 'Select Slider', 'gridd' ),
		'description'     => esc_html__( 'Select the slider you want to assign to this grid-part.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_revolution-slider',
		'default'         => '',
		'priority'        => 10,
		'transport'       => 'refresh',
		'choices'         => $sliders_array,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_revslider_slider_template' => [
				'selector'            => '.gridd-tp-revolution-slider',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'revolution-slider' );
				},
			],
		],
	]
);

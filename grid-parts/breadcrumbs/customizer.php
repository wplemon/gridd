<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

gridd_add_customizer_section(
	'gridd_grid_part_details_breadcrumbs',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_attr__( '%s Options', 'gridd' ), esc_html__( 'Breadcrumbs', 'gridd' ) ),
		'section'     => 'gridd_grid',
		'description' => sprintf(
			'<div class="gridd-section-description">%1$s%2$s</div>',
			( ! Gridd::is_pro() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> for extra options in this section: Automatic WCAG-compliant colors suggestion and spacing between breadcrumbs.', 'gridd' ) . '</div>' : '',
			'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/breadcrumbs/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
		),
		'priority'    => 20,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'text',
		'settings'    => 'gridd_grid_breadcrumbs_padding',
		'label'       => esc_attr__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Inner padding for this grid-part. Use any valid CSS value.', 'gridd' ),
		'tooltip'     => gridd()->customizer->get_text( 'padding' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '1em',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-breadcrumbs-padding',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_breadcrumbs_max_width',
		'label'       => esc_attr__( 'Max-Width', 'gridd' ),
		'description' => gridd()->customizer->get_text( 'grid-part-max-width' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '',
		'css_vars'    => '--gridd-breadcrumbs-max-width',
		'transport'   => 'postMessage',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_breadcrumbs_font_size',
		'label'       => esc_attr__( 'Font Size', 'gridd' ),
		'description' => esc_html__( 'Controls the font-size for your breadcrumbs.', 'gridd' ),
		'tooltip'     => gridd()->customizer->get_text( 'related-font-size' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => 1,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-breadcrumbs-font-size', '$em' ],
		'choices'     => [
			'min'    => .5,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_breadcrumbs_background_color',
		'label'       => esc_attr__( 'Background Color', 'gridd' ),
		'description' => esc_html__( 'Set the background-color for this grid-part.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => '#ffffff',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-breadcrumbs-bg',
		'choices'     => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'kirki-wcag-tc',
		'settings'    => 'gridd_grid_breadcrumbs_color',
		'label'       => esc_attr__( 'Text Color', 'gridd' ),
		'description' => gridd()->customizer->get_text( 'a11y-textcolor-description' ),
		'tooltip'     => gridd()->customizer->get_text( 'a11y-textcolor-tooltip' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'css_vars'    => '--gridd-breadcrumbs-color',
		'default'     => '#000000',
		'transport'   => 'postMessage',
		'choices'     => [
			'setting' => 'gridd_grid_breadcrumbs_background_color',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'radio',
		'settings'    => 'gridd_grid_breadcrumbs_text_align',
		'label'       => esc_attr__( 'Alignment', 'gridd' ),
		'description' => esc_html__( 'Select if you want your breadcrumbs aligned to the left, right, or centered.', 'gridd' ),
		'tooltip'     => esc_html__( 'Please note that this option does not change the order of your breadcrumbs, only their alignment inside their container.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_breadcrumbs',
		'default'     => 'left',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-breadcrumbs-text-align',
		'choices'     => [
			'left'   => esc_html__( 'Left', 'gridd' ),
			'center' => esc_html__( 'Center', 'gridd' ),
			'right'  => esc_html__( 'Right', 'gridd' ),
		],
	]
);

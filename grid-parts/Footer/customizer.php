<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\Grid_Part\Footer;

$sanitization = new Sanitize();

new \Kirki\Section(
	'grid_part_details_footer',
	[
		'title'    => esc_html__( 'Footer Grid', 'gridd' ),
		'priority' => 20,
		'panel'    => 'layout_options',
		'type'     => 'expanded',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_footer_grid',
		'section'           => 'grid_part_details_footer',
		'type'              => 'gridd_grid',
		'grid-part'         => 'footer',
		'label'             => esc_html__( 'Footer Grid', 'gridd' ),
		'description'       => __( 'You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
		'default'           => Footer::get_grid_defaults(),
		'choices'           => [
			'parts' => Footer::get_footer_grid_parts(),
		],
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'priority'          => 10,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'  => 'gridd_grid_footer_max_width',
		'label'     => esc_html__( 'Footer Maximum Width', 'gridd' ),
		'section'   => 'grid_part_details_footer',
		'default'   => '100%',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--mw',
			],
		],
		'priority'  => 20,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_grid_footer_grid_gap',
		'label'       => esc_html__( 'Grid Gap', 'gridd' ),
		'description' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'grid_part_details_footer',
		'default'     => '0',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--gg',
			],
		],
		'priority'    => 30,
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gridd_grid_footer_padding',
		'label'       => esc_html__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Inner padding for all parts in the footer.', 'gridd' ),
		'section'     => 'grid_part_details_footer',
		'default'     => '1em',
		'transport'   => 'auto',
		'output'      => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--pd',
			],
		],
		'priority'    => 40,
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'  => 'gridd_grid_footer_background_color',
		'label'     => esc_html__( 'Background Color', 'gridd' ),
		'section'   => 'grid_part_details_footer',
		'default'   => '#ffffff',
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bg',
			],
		],
		'choices'   => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'priority'  => 50,
	]
);

new \Kirki\Field\Slider(
	[
		'settings'  => 'gridd_grid_footer_border_top_width',
		'label'     => esc_html__( 'Border-Top Width', 'gridd' ),
		'section'   => 'grid_part_details_footer',
		'default'   => 0,
		'transport' => 'auto',
		'output'    => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-w',
			],
		],
		'priority'  => 50,
		'choices'   => [
			'min'    => 0,
			'max'    => 30,
			'step'   => 1,
			'suffix' => 'px',
		],
		'priority'  => 70,
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'gridd_grid_footer_border_top_color',
		'label'           => esc_html__( 'Top Border Color', 'gridd' ),
		'section'         => 'grid_part_details_footer',
		'default'         => '',
		'priority'        => 60,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-footer',
				'property' => '--bt-cl',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_footer_border_top_width',
				'operator' => '!=',
				'value'    => 0,
			],
		],
		'priority'        => 80,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

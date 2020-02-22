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

new \Kirki\Section(
	'gridd_reusable_block_instructions',
	[
		'title'    => '',
		'priority' => -999,
		'panel'    => 'layout_options',
		'type'     => 'kirki-expanded',
	]
);

new \Kirki\Field\Custom(
	[
		'settings'    => 'gridd_reusable_block_instructions',
		'label'       => '',
		'description' => sprintf(
			/* Translators: 1: URL to create a new reusable block. 2: URL to edit existing blocks. */
			'<p class="notice notice-info notice-alt" style="padding:12px">' . __( 'Use reusable blocks to add more functionality and grid-parts to your site. You can <a href="%1$s" target="_blank">Add a new reusable block</a>, or <a href="%2$s" target="_blank">Edit existing reusable blocks</a>.', 'gridd' ) . '</p>',
			esc_url( admin_url( 'post-new.php?post_type=wp_block' ) ),
			esc_url( admin_url( 'edit.php?post_type=wp_block' ) )
		),
		'section'     => 'gridd_reusable_block_instructions',
	]
);

new \Kirki\Section(
	'gridd_grid',
	[
		'title'    => esc_html__( 'Main Grid Layout', 'gridd' ),
		'priority' => -100,
		'panel'    => 'layout_options',
		'type'     => 'kirki-expanded',
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_grid',
		'section'           => 'gridd_grid',
		'type'              => 'gridd_grid',
		'grid-part'         => false,
		'label'             => esc_html__( 'Global Site Grid', 'gridd' ),
		'description'       => __( 'For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
		'default'           => Grid::get_grid_default_value(),
		'priority'          => 10,
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'choices'           => [
			'parts'     => Grid_Parts::get_instance()->get_parts(),
			'duplicate' => 'mobile',
		],
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'gap',
		'label'       => esc_html__( 'Grid Container Gap', 'gridd' ),
		'description' => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '0',
		'transport'   => 'auto',
		'priority'    => 30,
		'output'      => [
			[
				'element'  => '.gridd-site-wrapper',
				'property' => '--gg',
			],
		],
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'    => 'max_width',
		'label'       => esc_html__( 'Site Maximum Width', 'gridd' ),
		'description' => esc_html__( 'Set the value to something other than 100% to use a boxed layout.', 'gridd' ),
		'section'     => 'gridd_grid',
		'default'     => '100%',
		'priority'    => 40,
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => ':root',
				'property' => '--mw',
			],
		],
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

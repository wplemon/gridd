<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\AMP;

$number = Sidebar::get_number_of_sidebars();
for ( $i = 1; $i <= $number; $i++ ) {
	gridd_sidebar_customizer_options( $i );
}

/**
 * This function creates all options for a sidebar.
 * We use a parameter since we'll allow multiple sidebars.
 *
 * @since 1.0
 * @param int $id The number of this sidebar.
 * @return void
 */
function gridd_sidebar_customizer_options( $id ) {

	$sanitization = new Sanitize();

	/* translators: The number of the widget area. */
	$label = get_theme_mod( "gridd_grid_widget_area_{$id}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $id ) ) );

	Customizer::add_outer_section(
		"gridd_grid_part_details_sidebar_$id",
		[
			/* translators: The grid-part label. */
			'title' => sprintf( esc_html__( '%s Advanced Options', 'gridd' ), $label ),
		]
	);

	/**
	 * Focus on widget-area.
	 */
	Customizer::add_field(
		[
			'settings' => "gridd_sidebar_focus_on_sidebar_{$id}_section",
			'type'     => 'custom',
			'label'    => '',
			'section'  => "gridd_grid_part_details_sidebar_$id",
			'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="sidebar-widgets-' . "sidebar-{$id}" . '">' . esc_html__( 'Click here to edit your widgets', 'gridd' ) . '</button></div>',
		]
	);

	Customizer::add_field(
		[
			'type'      => 'color',
			'settings'  => "gridd_grid_sidebar_{$id}_background_color",
			'label'     => esc_html__( 'Background Color', 'gridd' ),
			'section'   => "gridd_grid_part_details_sidebar_$id",
			'default'   => '#ffffff',
			'priority'  => 10,
			'transport' => 'postMessage',
			'choices'   => [
				'alpha' => true,
			],
			'css_vars'  => "--gridd-sidebar-{$id}-bg",
			'choices'   => [
				'alpha' => true,
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'settings'          => "gridd_grid_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "gridd_grid_part_details_sidebar_$id",
			'default'           => '#000000',
			'priority'          => 20,
			'transport'         => 'postMessage',
			'choices'           => [
				'setting' => "gridd_grid_sidebar_{$id}_background_color",
			],
			'css_vars'          => "--gridd-sidebar-{$id}-color",
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-lc',
			'settings'          => "gridd_grid_sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "gridd_grid_part_details_sidebar_$id",
			'default'           => '#0f5e97',
			'priority'          => 30,
			'transport'         => 'postMessage',
			'choices'           => [
				'alpha' => true,
			],
			'css_vars'          => "--gridd-sidebar-{$id}-links-color",
			'choices'           => [
				'backgroundColor' => "gridd_grid_sidebar_{$id}_background_color",
				'textColor'       => "gridd_grid_sidebar_{$id}_color",
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	Customizer::add_field(
		[
			'type'        => 'dimension',
			'settings'    => "gridd_grid_sidebar_{$id}_padding",
			'label'       => esc_html__( 'Padding', 'gridd' ),
			'description' => Customizer::get_control_description(
				[
					'short'   => '',
					'details' => esc_html__( 'Use any valid CSS value. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
				]
			),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'priority'    => 40,
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-sidebar-{$id}-padding",
		]
	);

	Customizer::add_field(
		[
			'type'      => 'dimension',
			'settings'  => "gridd_grid_sidebar_{$id}_widgets_margin",
			'label'     => esc_html__( 'Margin between widgets', 'gridd' ),
			'section'   => "gridd_grid_part_details_sidebar_$id",
			'priority'  => 43,
			'default'   => '1em',
			'transport' => 'postMessage',
			'css_vars'  => "--gridd-sidebar-{$id}-margin",
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

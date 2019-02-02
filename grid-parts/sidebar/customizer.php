<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Customizer;
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

	/* translators: The number of the widget area. */
	$label = get_theme_mod( "gridd_grid_widget_area_{$id}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $id ) ) );

	gridd_add_customizer_section(
		"gridd_grid_part_details_sidebar_$id",
		[
			/* translators: The grid-part label. */
			'title'       => sprintf( esc_html__( '%s Advanced Options', 'gridd' ), $label ),
			'description' => Customizer::section_description(
				[
					'plus' => [
						esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
					],
					'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/widget-area/',
				]
			),
			'section'     => 'gridd_grid',
		]
	);

	/**
	 * Focus on widget-area.
	 */
	gridd_add_customizer_field(
		[
			'settings' => "gridd_sidebar_focus_on_sidebar_{$id}_section",
			'type'     => 'custom',
			'label'    => '',
			'section'  => "gridd_grid_part_details_sidebar_$id",
			'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="sidebar-widgets-' . "sidebar-{$id}" . '">' . esc_html__( 'Click here to edit your widgets', 'gridd' ) . '</button></div>',
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'color',
			'settings'    => "gridd_grid_sidebar_{$id}_background_color",
			'label'       => esc_html__( 'Background Color', 'gridd' ),
			'description' => esc_html__( 'Select the background color for this area', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'default'     => '#ffffff',
			'priority'    => 10,
			'transport'   => 'auto',
			'choices'     => [
				'alpha' => true,
			],
			'output'      => [
				[
					'element'  => ".gridd-tp-sidebar_{$id}",
					'property' => 'background-color',
				],
			],
			'css_vars'    => "--gridd-sidebar-{$id}-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'gridd-wcag-tc',
			'settings'    => "gridd_grid_sidebar_{$id}_color",
			'label'       => esc_html__( 'Text Color', 'gridd' ),
			'description' => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'default'     => '#000000',
			'priority'    => 20,
			'transport'   => 'auto',
			'choices'     => [
				'setting' => "gridd_grid_sidebar_{$id}_background_color",
			],
			'output'      => [
				[
					'element'  => ".gridd-tp-sidebar_{$id},.gridd-tp-sidebar_{$id} h1,.gridd-tp-sidebar_{$id} h2,.gridd-tp-sidebar_{$id} h3,.gridd-tp-sidebar_{$id} h4,.gridd-tp-sidebar_{$id} h5,.gridd-tp-sidebar_{$id} h6,.gridd-tp-sidebar_{$id} .widget-title",
					'property' => 'color',
				],
			],
			'css_vars'    => "--gridd-sidebar-{$id}-color",
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'gridd-wcag-lc',
			'settings'    => "gridd_grid_sidebar_{$id}_links_color",
			'label'       => esc_html__( 'Links Color', 'gridd' ),
			'description' => esc_html__( 'Select the color used for your links. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'default'     => '#0f5e97',
			'priority'    => 30,
			'transport'   => 'auto',
			'choices'     => [
				'alpha' => true,
			],
			'output'      => [
				[
					'element'  => [
						".gridd-tp-sidebar_{$id} a",
						".gridd-tp-sidebar_{$id} a:visited",
						".gridd-tp-sidebar_{$id} a:hover",
						".gridd-tp-sidebar_{$id} a:focus",
						".gridd-tp-sidebar_{$id} a:visited:hover",
						".gridd-tp-sidebar_{$id} a:visited:focus",
					],
					'property' => 'color',
				],
			],
			'css_vars'    => "--gridd-sidebar-{$id}-links-color",
			'choices'     => [
				'backgroundColor' => "gridd_grid_sidebar_{$id}_background_color",
				'textColor'       => "gridd_grid_sidebar_{$id}_color",
			],
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'text',
			'settings'    => "gridd_grid_sidebar_{$id}_padding",
			'label'       => esc_html__( 'Padding', 'gridd' ),
			'description' => __( 'The padding for this area. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'priority'    => 40,
			'default'     => '1em',
			'transport'   => 'auto',
			'output'      => [
				[
					'element'  => ".gridd-tp-sidebar_{$id}",
					'property' => 'padding',
				],
			],
			'css_vars'    => "--gridd-sidebar-{$id}-padding",
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'text',
			'settings'    => "gridd_grid_sidebar_{$id}_widgets_margin",
			'label'       => esc_html__( 'Margin between widgets', 'gridd' ),
			'description' => esc_html__( 'Changes the spacing between widgets in this widget-area.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'priority'    => 43,
			'default'     => '20px',
			'transport'   => 'auto',
			'output'      => [
				[
					'element'  => ".gridd-tp-sidebar_{$id} .widget",
					'property' => 'margin-bottom',
				],
			],
			'css_vars'    => "--gridd-sidebar-{$id}-margin",
		]
	);
}

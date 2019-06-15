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

	/**
	 * Add Customizer sections.
	 *
	 * @since 1.2
	 */
	add_action(
		'customize_register',
		/**
		 * Register sections.
		 *
		 * @since 1.2
		 * @param WP_Customize The WordPress Customizer main object.
		 * @return void
		 */
		function( $wp_customize ) use ( $id ) {

			/* translators: The number of the widget area. */
			$label = get_theme_mod( "gridd_grid_widget_area_{$id}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $id ) ) );

			$wp_customize->add_section(
				new \Kirki\Module\Custom_Sections\Section_Outer(
					$wp_customize,
					"gridd_grid_part_details_sidebar_$id",
					[
						/* translators: The grid-part label. */
						'title'       => sprintf( esc_html__( '%s Advanced Options', 'gridd' ), $label ),
						'panel'       => 'gridd_hidden_panel',
						'description' => Customizer::section_description(
							"gridd_grid_part_details_sidebar_$id",
							[
								'plus' => [
									esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
									esc_html__( 'Selecting from an array of WCAG-compliant colors for links', 'gridd' ),
									esc_html__( 'Visibility Options: Choose specific post-IDs or category/tag/term IDs to show this grid-part', 'gridd' ),
								],
								'docs' => 'https://wplemon.github.io/gridd/grid-parts/widget-area.html',
							]
						),
					]
				)
			);
		}
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
			'type'        => 'kirki-color',
			'settings'    => "gridd_grid_sidebar_{$id}_background_color",
			'label'       => esc_html__( 'Background Color', 'gridd' ),
			'description' => esc_html__( 'Select the background color for this area', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'default'     => '#ffffff',
			'priority'    => 10,
			'transport'   => 'postMessage',
			'choices'     => [
				'alpha' => true,
			],
			'css_vars'    => "--gridd-sidebar-{$id}-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'settings'          => "gridd_grid_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'description'       => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
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
			'description'       => esc_html__( 'Select the color used for your links. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
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
			'description' => __( 'The padding for this area. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'priority'    => 40,
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-sidebar-{$id}-padding",
		]
	);

	Customizer::add_field(
		[
			'type'        => 'dimension',
			'settings'    => "gridd_grid_sidebar_{$id}_widgets_margin",
			'label'       => esc_html__( 'Margin between widgets', 'gridd' ),
			'description' => esc_html__( 'Changes the spacing between widgets in this widget-area.', 'gridd' ),
			'section'     => "gridd_grid_part_details_sidebar_$id",
			'priority'    => 43,
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-sidebar-{$id}-margin",
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

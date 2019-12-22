<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Sidebar;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

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

	new \Kirki\Section(
		"grid_part_details_sidebar_$id",
		[
			'panel'           => 'theme_options',
			'title'           => $label,
			'active_callback' => '__return_false',
		]
	);

	new \Kirki\Field\Checkbox_Switch(
		[
			'settings'  => "gridd_grid_sidebar_{$id}_custom_options",
			'section'   => "grid_part_details_sidebar_$id",
			'default'   => false,
			'transport' => 'refresh',
			'priority'  => -999,
			'choices'   => [
				'off' => esc_html__( 'Inherit Options', 'gridd' ),
				'on'  => esc_html__( 'Override Options', 'gridd' ),
			],
		]
	);

	new \Kirki\Field\ReactColor(
		[
			'settings'        => "gridd_grid_sidebar_{$id}_background_color",
			'label'           => esc_html__( 'Background Color', 'gridd' ),
			'section'         => "grid_part_details_sidebar_$id",
			'default'         => '#ffffff',
			'priority'        => 10,
			'choices'         => [
				'alpha' => true,
			],
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--bg',
				],
			],
			'choices'         => [
				'formComponent' => 'TwitterPicker',
				'colors'        => \Gridd\Theme::get_colorpicker_palette(),
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "gridd_grid_sidebar_{$id}_custom_options", false );
			},
		]
	);

	new \WPLemon\Field\WCAGTextColor(
		[
			'settings'          => "gridd_grid_sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "grid_part_details_sidebar_$id",
			'default'           => '#000000',
			'priority'          => 20,
			'choices'           => [
				'backgroundColor' => "gridd_grid_sidebar_{$id}_background_color",
				'appearance'      => 'hidden',
			],
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--cl',
				],
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => function() use ( $id ) {
				return get_theme_mod( "gridd_grid_sidebar_{$id}_custom_options", false );
			},
		]
	);

	new \WPLemon\Field\WCAGLinkColor(
		[
			'settings'          => "gridd_grid_sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "grid_part_details_sidebar_$id",
			'default'           => '#0f5e97',
			'priority'          => 30,
			'choices'           => [
				'alpha' => true,
			],
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--lc',
				],
			],
			'choices'           => [
				'backgroundColor' => "gridd_grid_sidebar_{$id}_background_color",
				'textColor'       => "gridd_grid_sidebar_{$id}_color",
				'linksUnderlined' => true,
				'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => function() use ( $id ) {
				return get_theme_mod( "gridd_grid_sidebar_{$id}_custom_options", false );
			},
		]
	);

	Customizer::add_field(
		[
			'type'            => 'dimension',
			'settings'        => "gridd_grid_sidebar_{$id}_padding",
			'label'           => esc_html__( 'Padding', 'gridd' ),
			'description'     => Customizer::get_control_description(
				[
					'short'   => '',
					'details' => sprintf(
						/* translators: Link properties. */
						__( 'Use any valid CSS value. For details on how padding works, please refer to <a %s>this article</a>.', 'gridd' ),
						'href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow"'
					),
				]
			),
			'section'         => "grid_part_details_sidebar_$id.custom-options",
			'priority'        => 40,
			'default'         => '1em',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-sidebar_$id",
					'property' => '--pd',
				],
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "gridd_grid_sidebar_{$id}_custom_options", false );
			},
		]
	);

	Customizer::add_field(
		[
			'type'            => 'dimension',
			'settings'        => "gridd_grid_sidebar_{$id}_widgets_margin",
			'label'           => esc_html__( 'Margin between widgets', 'gridd' ),
			'section'         => "grid_part_details_sidebar_$id",
			'priority'        => 43,
			'default'         => '1em',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--mr',
				],
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "gridd_grid_sidebar_{$id}_custom_options", false );
			},
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

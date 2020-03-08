<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Sidebar;
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
	$label = get_theme_mod( "widget_area_{$id}_name", sprintf( esc_html__( 'Widget Area %d', 'gridd' ), intval( $id ) ) );

	new \Kirki\Section(
		"sidebar_$id",
		[
			'panel'           => 'theme_options',
			'title'           => $label,
			'active_callback' => '__return_false',
		]
	);

	new \Kirki\Field\Checkbox_Switch(
		[
			'settings'  => "sidebar_{$id}_custom_options",
			'section'   => "sidebar_$id",
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
			'settings'        => "sidebar_{$id}_background_color",
			'label'           => esc_html__( 'Background Color', 'gridd' ),
			'section'         => "sidebar_$id",
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
				return get_theme_mod( "sidebar_{$id}_custom_options", false );
			},
		]
	);

	new \WPLemon\Field\WCAGTextColor(
		[
			'settings'          => "sidebar_{$id}_color",
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'section'           => "sidebar_$id",
			'default'           => '#000000',
			'priority'          => 20,
			'choices'           => [
				'backgroundColor' => "sidebar_{$id}_background_color",
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
				return get_theme_mod( "sidebar_{$id}_custom_options", false );
			},
		]
	);

	new \WPLemon\Field\WCAGLinkColor(
		[
			'settings'          => "sidebar_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "sidebar_$id",
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
				'backgroundColor' => "sidebar_{$id}_background_color",
				'textColor'       => "sidebar_{$id}_color",
				'linksUnderlined' => true,
				'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => '__return_false',
		]
	);

	/**
	 * Deprecate option for now.
	new \Kirki\Field\Dimension(
		[
			'settings'        => "sidebar_{$id}_padding",
			'label'           => esc_html__( 'Padding', 'gridd' ),
			'description'     => esc_html__( 'Use any valid CSS value.', 'gridd' ),
			'section'         => "sidebar_$id",
			'priority'        => 40,
			'default'         => '1em',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--pd',
				],
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "sidebar_{$id}_custom_options", false );
			},
		]
	);
	 */

	new \Kirki\Field\Dimension(
		[
			'settings'        => "sidebar_{$id}_widgets_margin",
			'label'           => esc_html__( 'Margin between widgets', 'gridd' ),
			'section'         => "sidebar_$id",
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
				return get_theme_mod( "sidebar_{$id}_custom_options", false );
			},
		]
	);

	new \Kirki\Field\RadioButtonset(
		[
			'settings'        => "sidebar_{$id}_flex_direction",
			'label'           => esc_html__( 'Widgets Direction', 'gridd' ),
			'section'         => "sidebar_$id",
			'priority'        => 50,
			'default'         => 'column',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-sidebar_$id.custom-options",
					'property' => '--fd',
				],
			],
			'choices'         => [
				'column' => esc_html__( 'Vertical', 'gridd' ),
				'row'    => esc_html__( 'Horizontal', 'gridd' ),
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "sidebar_{$id}_custom_options", false );
			},
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

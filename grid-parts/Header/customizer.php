<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Header;
use Gridd\Grid_Parts;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

$sanitization = new Sanitize();

/**
 * Move the header-image control.
 *
 * @since 1.0
 * @param WP_Customize The WordPress Customizer main object.
 * @return void
 */
add_action(
	'customize_register',
	function( $wp_customize ) {
		$wp_customize->get_control( 'header_image' )->active_callback = '__return_false';
	}
);

new \Kirki\Section(
	'grid_part_details_header',
	[
		'title'    => esc_html__( 'Header Grid', 'gridd' ),
		'priority' => -80,
		'panel'    => 'theme_options',
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'  => 'header_custom_options',
		'section'   => 'grid_part_details_header',
		'default'   => false,
		'transport' => 'refresh',
		'priority'  => -999,
		'choices'   => [
			'off' => esc_html__( 'Inherit Options', 'gridd' ),
			'on'  => esc_html__( 'Override Options', 'gridd' ),
		],
	]
);

Customizer::add_field(
	[
		'settings'          => 'gridd_header_grid',
		'section'           => 'grid_part_details_header',
		'type'              => 'gridd_grid',
		'grid-part'         => 'header',
		'label'             => esc_html__( 'Header Grid', 'gridd' ),
		'description'       => __( 'You can add columns and rows, define their sizes, and also add or remove grid-parts on your site. For more information and documentation on how the grid works, please read <a href="https://wplemon.github.io/gridd/the-grid-control.html" target="_blank">this article</a>.', 'gridd' ),
		'default'           => Header::get_grid_defaults(),
		'choices'           => [
			'parts' => Header::get_header_grid_parts(),
		],
		'sanitize_callback' => [ $sanitization, 'grid' ],
		'priority'          => 10,
		/**
		 * Deactivated the partial-refresh due to https://github.com/wplemon/gridd/issues/89
		 *
		'transport'         => 'postMessage',
		'partial_refresh'   => [
			'gridd_header_grid_part_renderer' => [
				'selector'            => '.gridd-tp.gridd-tp-header',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'header' );
				},
			],
		],
		*/
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'header_max_width',
		'label'           => esc_html__( 'Header Maximum Width', 'gridd' ),
		'section'         => 'grid_part_details_header',
		'default'         => '100%',
		'priority'        => 20,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--mw',
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_custom_options', false );
		},
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'gridd_grid_header_padding',
		'label'           => esc_html__( 'Header Padding', 'gridd' ),
		'section'         => 'grid_part_details_header',
		'default'         => '0',
		'priority'        => 30,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--pd',
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_custom_options', false );
		},
	]
);

new \Kirki\Field\Dimension(
	[
		'settings'        => 'gridd_grid_header_grid_gap',
		'label'           => esc_html__( 'Grid Gap', 'gridd' ),
		'description'     => __( 'Adds a gap between your grid-parts, both horizontally and vertically. For more information please read <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/gap" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
		'section'         => 'grid_part_details_header',
		'default'         => '0',
		'priority'        => 40,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--gg',
			],
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_custom_options', false );
		},
	]
);

new \Kirki\Field\ReactColor(
	[
		'settings'        => 'header_background_color',
		'label'           => esc_html__( 'Background Color', 'gridd' ),
		'section'         => 'grid_part_details_header',
		'default'         => '#ffffff',
		'priority'        => 50,
		'transport'       => 'auto',
		'output'          => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--bg',
			],
		],
		'choices'         => [
			'formComponent' => 'TwitterPicker',
			'colors'        => \Gridd\Theme::get_colorpicker_palette(),
		],
		'active_callback' => function() {
			return get_theme_mod( 'header_custom_options', false );
		},
	]
);

new \WPLemon\Field\WCAGTextColor(
	[
		'settings'          => 'header_text_color',
		'label'             => esc_html__( 'Text Color', 'gridd' ),
		'section'           => 'grid_part_details_header',
		'priority'          => 51,
		'default'           => '#000000',
		'output'            => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--cl',
			],
		],
		'transport'         => 'auto',
		'choices'           => [
			'backgroundColor' => 'header_background_color',
			'appearance'      => 'hidden',
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
	]
);

new \WPLemon\Field\WCAGLinkColor(
	[
		'settings'          => 'header_links_color',
		'label'             => esc_html__( 'Links Color', 'gridd' ),
		'description'       => esc_html__( 'Select the hue for you links. The color will be auto-calculated to ensure maximum readability according to WCAG.', 'gridd' ),
		'section'           => 'grid_part_details_header',
		'transport'         => 'auto',
		'priority'          => 52,
		'choices'           => [
			'alpha' => false,
		],
		'default'           => '#0f5e97',
		'choices'           => [
			'backgroundColor' => 'header_background_color',
			'textColor'       => 'header_text_color',
			'linksUnderlined' => true,
			'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
		],
		'output'            => [
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--lc',
			],
			[
				'element'  => '.gridd-tp-header.custom-options',
				'property' => '--acl',
			],
		],
		'sanitize_callback' => [ $sanitization, 'color_hex' ],
		'active_callback'   => function() {
			return get_theme_mod( 'header_custom_options', false );
		},
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'          => 'gridd_grid_header_box_shadow',
		'label'             => esc_html__( 'Drop Shadow Intensity', 'gridd' ),
		'description'       => esc_html__( 'Set to "None" to disable the shadow, or increase the intensity for a more dramatic effect.', 'gridd' ),
		'section'           => 'grid_part_details_header',
		'default'           => 'none',
		'transport'         => 'postMessage',
		'transport'         => 'auto',
		'output'            => [
			[
				'element'  => '.gridd-tp-header',
				'property' => '--bs',
			],
		],
		'priority'          => 70,
		'choices'           => [
			'none' => esc_html__( 'None', 'gridd' ),
			'0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)' => esc_html__( 'Extra Light', 'gridd' ),
			'0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23)' => esc_html__( 'Light', 'gridd' ),
			'0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)' => esc_html__( 'Medium', 'gridd' ),
			'0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)' => esc_html__( 'Heavy', 'gridd' ),
			'0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22)' => esc_html__( 'Extra Heavy', 'gridd' ),
		],
		'sanitize_callback' => 'sanitize_text_field',
	]
);

new \Kirki\Field\Checkbox_Toggle(
	[
		'settings'    => 'gridd_header_sticky',
		'label'       => esc_html__( 'Sticky on Large Devices', 'gridd' ),
		'description' => esc_html__( 'Enable to stick this area to the top of the page when users scroll-down on devices larger than the breakpoint you defined in your main grid.', 'gridd' ),
		'section'     => 'grid_part_details_header',
		'default'     => false,
		'transport'   => 'refresh',
		'priority'    => 80,
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

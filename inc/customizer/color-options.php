<?php
/**
 * Color Options.
 *
 * @package Gridd
 * @since 1.2.0
 */

use Gridd\Customizer;

Customizer::add_section(
	'color_options',
	[
		'title'    => esc_attr__( 'Color Options', 'gridd' ),
		'priority' => -100,
	]
);

new \WPLemon\Field\PaletteBuilder(
	[
		'settings'          => 'custom_color_palette',
		'label'             => esc_attr__( 'Color Palette', 'gridd' ),
		'description'       => esc_html__( 'Colors selected here will affect the palette available in all controls, as well as the palette in the WordPress editor.', 'gridd' ),
		'section'           => 'color_options',
		'default'           => \Gridd\Theme::get_color_palette( true ),
		'priority'          => -40,
		'sanitize_callback' => function( $val ) {
			if ( is_string( $val ) ) {
				$val = json_decode( $val );
			}
			$value = [];
			foreach ( $val as $key => $item ) {
				$value[] = [
					'name'  => sanitize_text_field( $item['slug'] ),
					'slug'  => sanitize_text_field( $item['slug'] ),
					'color' => sanitize_hex_color( $item['color'] ),
				];
			}
			return $value;
		},
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'same_linkcolor_hues',
		'label'       => esc_attr__( 'Consistent Hue', 'gridd' ),
		'description' => esc_html__( 'By default all your grid-parts will use the same link-color hue. Disable this option to access individual settings on some grid-parts.', 'gridd' ),
		'section'     => 'color_options',
		'default'     => true,
		'transport'   => 'postMessage',
		'priority'    => 1,
		'sanitize_callback' => function( $value ) {
			return (bool) $value;
		},
	]
);

new \Kirki\Field\RadioButtonset(
	[
		'settings'    => 'target_color_compliance',
		'label'       => esc_attr__( 'Color Accessibility Target Compliance', 'gridd' ),
		'description' => esc_html__( 'Select how text and link colors will be calculated.', 'gridd' ),
		'section'     => 'color_options',
		'default'     => 'auto',
		'transport'   => 'postMessage',
		'priority'    => 100,
		'choices'     => [
			'auto' => esc_html__( 'Auto', 'gridd' ) . '<div class="radio-tooltip">' . esc_html__( 'Select "Auto" mode to automatically get colors with maximum contrast to get as close to a higher accessibility standard as possible. It is similar to AAA compliance, with a fallback to above-AA colors is AAA is not achievable.', 'gridd' ) . '</div>',
			'AAA'  => 'AAA' . '<div class="radio-tooltip">' . esc_html__( 'Targeting for "AAA" compliance results in less intense but easier to read colors.', 'gridd' ) . '</div>',
			'AA'   => 'AA' . '<div class="radio-tooltip">' . esc_html__( 'Targeting for "AA" compliance will result in more vibrant colors while still maintaining a readable color contrast.', 'gridd' ) . '</div>',
		],
		'sanitize_callback' => function( $value ) {
			return ( 'auto' === $value || 'AAA' === $value || 'AA' === $value ) ? $value : 'auto';
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

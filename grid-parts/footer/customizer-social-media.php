<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Footer;

// Include Social Icons Definitions.
require_once get_template_directory() . '/inc/social-icons.php';

gridd_add_customizer_section(
	'gridd_grid_part_details_footer_social_media',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_attr__( '%s Options', 'gridd' ), esc_html__( 'Footer Contact Info', 'gridd' ) ),
		'section'     => 'gridd_grid',
		'description' => '<div class="gridd-section-description"><div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/footer/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div></div>',
		'priority'    => 20,
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'repeater',
		'settings'        => 'gridd_grid_part_details_footer_social_icons',
		'label'           => esc_attr__( 'Social Media Links', 'gridd' ),
		'section'         => 'gridd_grid_part_details_footer_social_media',
		'default'         => Footer::get_social_icons_default_value(),
		'row_label'       => [
			'type'  => 'field',
			'field' => 'icon',
		],
		'button_label'    => esc_html__( 'Add Icon', 'gridd' ),
		'fields'          => [
			'icon' => [
				'type'    => 'select',
				'label'   => esc_attr__( 'Select Icon', 'gridd' ),
				'default' => '',
				'choices' => gridd_social_icons_svg( 'keys_only' ),
			],
			'url'  => [
				'type'    => 'text',
				'label'   => esc_attr__( 'Link URL', 'gridd' ),
				'default' => '',
			],
		],
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_part_details_footer_social_icons_template' => [
				'selector'            => '.gridd-tp-footer_social_media',
				'container_inclusive' => false,
				'render_callback'     => function() {
					get_template_part( 'grid-parts/footer/template-social-media' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_part_details_footer_social_icons_size',
		'label'       => esc_attr__( 'Size', 'gridd' ),
		'description' => esc_html__( 'Controls the size for social-media icons.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer_social_media',
		'default'     => 1,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-footer-social-icons-size', '$em' ],
		'choices'     => [
			'min'    => .3,
			'max'    => 3,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_grid_part_details_footer_social_icons_padding',
		'label'       => esc_attr__( 'Padding', 'gridd' ),
		'description' => esc_html__( 'Controls the padding for social-media icons.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer_social_media',
		'default'     => .5,
		'transport'   => 'postMessage',
		'css_vars'    => [ '--gridd-footer-social-icons-padding', '$em' ],
		'choices'     => [
			'min'    => 0,
			'max'    => 2,
			'step'   => .01,
			'suffix' => 'em',
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_footer_social_icons_background_color',
		'label'     => esc_attr__( 'Background Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_social_media',
		'default'   => '#ffffff',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-social-icons-bg',
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'      => 'color',
		'settings'  => 'gridd_grid_part_details_footer_social_icons_icons_color',
		'label'     => esc_attr__( 'Icons Color', 'gridd' ),
		'section'   => 'gridd_grid_part_details_footer_social_media',
		'default'   => '#000000',
		'transport' => 'postMessage',
		'css_vars'  => '--gridd-footer-social-icons-color',
		'choices'   => [
			'alpha' => true,
		],
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'radio-buttonset',
		'settings'    => 'gridd_grid_part_details_footer_social_icons_icons_text_align',
		'label'       => esc_attr__( 'Text Align', 'gridd' ),
		'description' => esc_html__( 'Select how the icons will be aligned.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_footer_social_media',
		'default'     => 'right',
		'transport'   => 'postMessage',
		'css_vars'    => '--gridd-footer-social-icons-text-align',
		'choices'     => [
			'flex-start' => esc_html__( 'Left', 'gridd' ),
			'center'     => esc_html__( 'Center', 'gridd' ),
			'flex-end'   => esc_html__( 'Right', 'gridd' ),
		],
	]
);

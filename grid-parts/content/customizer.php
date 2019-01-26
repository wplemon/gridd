<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

gridd_add_customizer_section(
	'gridd_grid_part_details_content',
	[
		/* translators: The grid-part label. */
		'title'       => sprintf( esc_attr__( '%s Options', 'gridd' ), esc_html__( 'Content', 'gridd' ) ),
		'section'     => 'gridd_grid',
		'description' => '<div class="gridd-section-description"><div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/content/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div></div>',
		'priority'    => 90,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_grid_content_max_width',
		'label'       => esc_attr__( 'Max-Width', 'gridd' ),
		'description' => gridd()->customizer->get_text( 'grid-part-max-width' ),
		'section'     => 'gridd_grid_part_details_content',
		'default'     => '45em',
		'css_vars'    => '--gridd-content-max-width',
		'transport'   => 'postMessage',
		'priority'    => 10,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'dimensions',
		'settings'    => 'gridd_grid_content_padding',
		'label'       => esc_attr__( 'Container Padding', 'gridd' ),
		'description' => esc_html__( 'Please enter values for the content area\'s padding.', 'gridd' ),
		'section'     => 'gridd_grid_part_details_content',
		'default'     => [
			'top'    => '0px',
			'bottom' => '0px',
			'left'   => '20px',
			'right'  => '20px',
		],
		'css_vars'    => [
			[ '--gridd-content-padding-top', '$', 'top' ],
			[ '--gridd-content-padding-bottom', '$', 'bottom' ],
			[ '--gridd-content-padding-left', '$', 'left' ],
			[ '--gridd-content-padding-right', '$', 'right' ],
		],
		'transport'   => 'postMessage',
		'priority'    => 15,
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'color',
		'settings'    => 'gridd_grid_content_background_color',
		'label'       => esc_attr__( 'Background Color', 'gridd' ),
		'description' => esc_html__( 'Background Color for the content area. Always prefer light backgrounds with dark text for increased accessibility', 'gridd' ),
		'section'     => 'gridd_grid_part_details_content',
		'default'     => '#ffffff',
		'css_vars'    => '--gridd-content-bg',
		'transport'   => 'postMessage',
		'priority'    => 30,
		'choices'     => [
			'alpha' => true,
		],
	]
);

/**
 * Focus on title_tagline section.
 */
gridd_add_customizer_field(
	[
		'settings' => 'gridd_logo_focus_on_typography_section',
		'type'     => 'custom',
		'label'    => 'Looking for the text-color and typography options?',
		'section'  => 'gridd_grid_part_details_content',
		'priority' => 32,
		'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button-primary button-large" data-context="section" data-focus="gridd_typography">' . esc_html__( 'Click here to edit typography options', 'gridd' ) . '</button></div>',
	]
);

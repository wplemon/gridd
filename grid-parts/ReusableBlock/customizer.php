<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\ReusableBlock;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

/**
 * Register the menus.
 *
 * @since 1.0
 * @return void
 */
function gridd_add_reusable_blocks_parts() {
	$reusable_blocks = ReusableBlock::get_reusable_blocks();
	if ( $reusable_blocks ) {
		foreach ( $reusable_blocks as $block ) {
			gridd_reusable_blocks_customizer_options( $block->ID );
		}
	}
}
add_action( 'after_setup_theme', 'gridd_add_reusable_blocks_parts' );

/**
 * This function creates all options for a reusable block.
 * We use a parameter since we'll allow multiple reusable blocks.
 *
 * @since 1.0
 * @param int $id The number of this reusable block.
 * @return void
 */
function gridd_reusable_blocks_customizer_options( $id ) {

	$sanitization = new Sanitize();

	/**
	 * Add Customizer Sections.
	 */
	new \Kirki\Section(
		"grid_part_details_reusable_block_$id",
		[
			/* translators: The navigation number. */
			'title'           => sprintf( esc_html__( 'Block: %s', 'gridd' ), esc_html( get_the_title( $id ) ) ),
			'priority'        => 30,
			'type'            => 'kirki-expanded',
			'panel'           => 'theme_options',
			'active_callback' => function() use ( $id ) {
				return \Gridd\Customizer::is_section_active_part( "reusable_block_$id" );
			},
		]
	);

	new \Kirki\Field\Checkbox_Switch(
		[
			'settings'  => "reusable_block_{$id}_custom_options",
			'section'   => "grid_part_details_reusable_block_$id",
			'default'   => false,
			'transport' => 'refresh',
			'priority'  => -999,
			'choices'   => [
				'off' => esc_html__( 'Inherit Options', 'gridd' ),
				'on'  => esc_html__( 'Override Options', 'gridd' ),
			],
		]
	);

	new \Kirki\Field\Custom(
		[
			'settings'    => "gridd_grid_reusable_block_{$id}_help",
			'description' => '<a href="' . esc_url( admin_url( 'edit.php?post_type=wp_block' ) ) . '" target="_blank">' . esc_html__( ' Manage reusable blocks', 'gridd' ) . '</a>',
			'section'     => "grid_part_details_reusable_block_$id",
			'default'     => '',
		]
	);

	new \Kirki\Field\Dimension(
		[
			'settings'        => "gridd_grid_reusable_block_{$id}_padding",
			'label'           => esc_html__( 'Padding', 'gridd' ),
			'description'     => esc_html__( 'Use any valid CSS value.', 'gridd' ),
			'section'         => "grid_part_details_reusable_block_$id",
			'default'         => '1em',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-reusable_block_$id.custom-options",
					'property' => '--pd',
				],
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "reusable_block_{$id}_custom_options", false );
			},
		]
	);

	new \Kirki\Field\ReactColor(
		[
			'label'           => esc_html__( 'Background Color', 'gridd' ),
			'settings'        => "gridd_grid_reusable_block_{$id}_bg_color",
			'section'         => "grid_part_details_reusable_block_$id",
			'default'         => '#ffffff',
			'transport'       => 'auto',
			'output'          => [
				[
					'element'  => ".gridd-tp-reusable_block_$id.custom-options",
					'property' => '--bg',
				],
			],
			'choices'         => [
				'formComponent' => 'TwitterPicker',
				'colors'        => \Gridd\Theme::get_colorpicker_palette(),
			],
			'active_callback' => function() use ( $id ) {
				return get_theme_mod( "reusable_block_{$id}_custom_options", false );
			},
		]
	);

	new \WPLemon\Field\WCAGTextColor(
		[
			'settings'          => "gridd_grid_reusable_block_{$id}_color",
			'section'           => "grid_part_details_reusable_block_$id",
			'choices'           => [
				'backgroundColor' => "gridd_grid_reusable_block_{$id}_bg_color",
				'appearance'      => 'hidden',
			],
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'priority'          => 30,
			'default'           => '#000000',
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-reusable_block_$id.custom-options",
					'property' => '--cl',
				],
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	new \WPLemon\Field\WCAGLinkColor(
		[
			'settings'          => "gridd_grid_reusable_block_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "grid_part_details_reusable_block_$id",
			'default'           => '#0f5e97',
			'priority'          => 40,
			'choices'           => [
				'alpha' => false,
			],
			'transport'         => 'auto',
			'output'            => [
				[
					'element'  => ".gridd-tp-reusable_block_$id.custom-options",
					'property' => '--lc',
				],
			],
			'choices'           => [
				'backgroundColor' => "gridd_grid_reusable_block_{$id}_bg_color",
				'textColor'       => "gridd_grid_reusable_block_{$id}_color",
				'linksUnderlined' => true,
				'forceCompliance' => get_theme_mod( 'target_color_compliance', 'auto' ),
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
			'active_callback'   => function() use ( $id ) {
				return get_theme_mod( "reusable_block_{$id}_custom_options", false );
			},
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

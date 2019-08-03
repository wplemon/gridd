<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Reusable_Block;
use Gridd\AMP;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;

/**
 * Register the menus.
 *
 * @since 1.0
 * @return void
 */
function gridd_add_reusable_blocks_parts() {
	$reusable_blocks = Reusable_Block::get_reusable_blocks();
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
	Customizer::add_outer_section(
		"gridd_grid_part_details_reusable_block_$id",
		[
			'title'       => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The reusable block number. */
				sprintf( esc_html__( 'Reusable Block %d', 'gridd' ), absint( $id ) )
			),
			'description' => Customizer::section_description(
				'gridd_grid_part_details_footer_copyright',
				[
					'plus' => [
						esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
						esc_html__( 'Selecting from an array of WCAG-compliant colors for links', 'gridd' ),
					],
					'docs' => 'https://wplemon.github.io/gridd/grid-parts/reusable-block.html',
				]
			),
		]
	);

	Customizer::add_field(
		[
			'type'        => 'custom',
			'settings'    => "gridd_grid_reusable_block_{$id}_help",
			'description' => '<a href="' . esc_url( admin_url( 'edit.php?post_type=wp_block' ) ) . '" target="_blank">' . esc_html__( ' Manage reusable blocks', 'gridd' ) . '</a>',
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'default'     => '',
		]
	);

	Customizer::add_field(
		[
			'type'        => 'dimension',
			'settings'    => "gridd_grid_reusable_block_{$id}_padding",
			'label'       => esc_html__( 'Padding', 'gridd' ),
			'description' => Customizer::get_control_description(
				[
					'short'   => '',
					'details' => esc_html__( 'Use any valid CSS value. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
				]
			),
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-reusable-block-$id-padding",
		]
	);

	Customizer::add_field(
		[
			'type'        => 'color',
			'label'       => esc_html__( 'Background Color', 'gridd' ),
			'settings'    => "gridd_grid_reusable_block_{$id}_bg_color",
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'default'     => '#ffffff',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-reusable-block-$id-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'settings'          => "gridd_grid_reusable_block_{$id}_color",
			'section'           => "gridd_grid_part_details_reusable_block_$id",
			'choices'           => [
				'setting' => "gridd_grid_reusable_block_{$id}_bg_color",
			],
			'label'             => esc_html__( 'Text Color', 'gridd' ),
			'priority'          => 30,
			'default'           => '#000000',
			'css_vars'          => "--gridd-reusable-block-$id-color",
			'transport'         => 'postMessage',
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-lc',
			'settings'          => "gridd_grid_reusable_block_{$id}_links_color",
			'label'             => esc_html__( 'Links Color', 'gridd' ),
			'section'           => "gridd_grid_part_details_reusable_block_$id",
			'default'           => '#0f5e97',
			'priority'          => 40,
			'transport'         => 'postMessage',
			'choices'           => [
				'alpha' => false,
			],
			'css_vars'          => "--gridd-reusable-block-{$id}-links-color",
			'choices'           => [
				'backgroundColor' => "gridd_grid_reusable_block_{$id}_bg_color",
				'textColor'       => "gridd_grid_reusable_block_{$id}_color",
			],
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

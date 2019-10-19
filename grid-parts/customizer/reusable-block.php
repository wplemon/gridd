<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Reusable_Block;
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
		"grid_part_details_reusable_block_$id",
		[
			'title' => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The reusable block number. */
				sprintf( esc_html__( 'Reusable Block %d', 'gridd' ), absint( $id ) )
			),
		]
	);

	Customizer::add_field(
		[
			'type'        => 'custom',
			'settings'    => "gridd_grid_reusable_block_{$id}_help",
			'description' => '<a href="' . esc_url( admin_url( 'edit.php?post_type=wp_block' ) ) . '" target="_blank">' . esc_html__( ' Manage reusable blocks', 'gridd' ) . '</a>',
			'section'     => "grid_part_details_reusable_block_$id",
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
					'details' => sprintf(
						/* translators: Link properties. */
						__( 'Use any valid CSS value. For details on how padding works, please refer to <a %s>this article</a>.', 'gridd' ),
						'href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow"'
					),
				]
			),
			'section'     => "grid_part_details_reusable_block_$id",
			'default'     => '1em',
			'transport'   => 'auto',
			'output'      => [
				[
					'element'  => ".gridd-tp-reusable_block_$id",
					'property' => '--pd',
				],
			],
		]
	);

	new \Kirki\Field\ReactColor(
		[
			'label'     => esc_html__( 'Background Color', 'gridd' ),
			'settings'  => "gridd_grid_reusable_block_{$id}_bg_color",
			'section'   => "grid_part_details_reusable_block_$id",
			'default'   => '#ffffff',
			'transport' => 'auto',
			'output'    => [
				[
					'element'  => ".gridd-tp-reusable_block_$id",
					'property' => '--bg',
				],
			],
			'choices'   => [
				'formComponent' => 'TwitterPicker',
				'colors'        => [ '#FFFFFF', '#fffcea', '#F9F9F9', '#f7f6e3', '#f7f7f7', '#f4f4e1', '#1A1A1A', '#000000', '#FF6900', '#FCB900', '#7BDCB5', '#00D084', '#8ED1FC', '#0693E3', '#ABB8C3', '#EB144C', '#F78DA7', '#9900EF' ],
			],
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
					'element'  => ".gridd-tp-reusable_block_$id",
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
					'element'  => ".gridd-tp-reusable_block_$id",
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
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

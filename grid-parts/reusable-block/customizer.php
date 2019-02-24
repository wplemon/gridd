<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

// phpcs:disable Squiz.Commenting.InlineComment.SpacingBefore
use Gridd\Grid_Part\Reusable_Block;
use Gridd\AMP;
use Gridd\Customizer;

/**
 * Register the menus.
 *
 * @since 1.0
 * @return void
 */
function gridd_add_reusable_blocks_parts() {
	$number = Reusable_Block::get_number_of_reusable_blocks_grid_parts();

	for ( $i = 1; $i <= $number; $i++ ) {
		gridd_reusable_blocks_customizer_options( $i );
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

	/**
	 * Add Customizer Sections.
	 */
	gridd_add_customizer_outer_section(
		"gridd_grid_part_details_reusable_block_$id",
		[
			'title' => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The reusable block number. */
				sprintf( esc_html__( 'Reusable Block %d', 'gridd' ), absint( $id ) )
			),
		]
	);

	$reusable_blocks       = [
		0 => esc_html__( '-- Select a reusable block --', 'gridd' ),
	];
	$saved_reusable_blocks = \Kirki_Helper::get_posts(
		[
			'posts_per_page' => -1,
			'post_type'      => 'wp_block',
		]
	);
	foreach ( $saved_reusable_blocks as $reusable_block_id => $reusable_block_title ) {
		$reusable_blocks[ $reusable_block_id ] = $reusable_block_title;
	}

	gridd_add_customizer_field(
		[
			'type'        => 'select',
			'settings'    => "gridd_grid_reusable_block_{$id}_id",
			'label'       => esc_html__( 'Reusable Block', 'gridd' ),
			'description' => sprintf(
				/* Translators: URL. */
				__( 'Select the reusable block to use or <a href="%s" target="_blank">manage reusable blocks</a>.', 'gridd' ),
				esc_url( admin_url( 'edit.php?post_type=wp_block' ) )
			),
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'default'     => 0,
			'transport'   => 'refresh',
			'choices'     => $reusable_blocks,
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'dimension',
			'settings'    => "gridd_grid_reusable_block_{$id}_padding",
			'label'       => esc_html__( 'Padding', 'gridd' ),
			'description' => __( 'Inner padding for this grid-part. Use any valid CSS value. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-reusable-block-$id-padding",
		]
	);

	gridd_add_customizer_field(
		[
			'type'        => 'color',
			'label'       => esc_html__( 'Background Color', 'gridd' ),
			'description' => esc_html__( 'Controls the overall background color for this grid-part.', 'gridd' ),
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

	gridd_add_customizer_field(
		[
			'type'        => 'gridd-wcag-tc',
			'settings'    => "gridd_grid_reusable_block_{$id}_color",
			'section'     => "gridd_grid_part_details_reusable_block_$id",
			'choices'     => [
				'setting' => "gridd_grid_reusable_block_{$id}_bg_color",
			],
			'label'       => esc_html__( 'Text Color', 'gridd' ),
			'description' => esc_html__( 'Select the color used for your text. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
			'priority'    => 30,
			'default'     => '#000000',
			'css_vars'    => "--gridd-reusable-block-$id-color",
			'transport'   => 'postMessage',
		]
	);
}

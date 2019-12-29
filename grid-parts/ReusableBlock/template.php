<?php
/**
 * Template part for the ReusableBlock.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Theme;
use Gridd\Blog;

$attributes = [
	'class' => "gridd-tp gridd-tp-reusable-block gridd-tp-reusable_block_$gridd_reusable_block_id",
];

if ( get_theme_mod( "reusable_block_{$gridd_reusable_block_id}_custom_options", false ) ) {
	$attributes['class'] .= ' custom-options';
}
?>

<div <?php Theme::print_attributes( $attributes, "reusable_block_$gridd_reusable_block_id" ); ?>>
	<div class="inner">
		<?php

		// Edit link.
		Blog::get_the_edit_link( $gridd_reusable_block_id, true );

		// The block.
		$gridd_reusable_block = get_post( $gridd_reusable_block_id );
		if ( $gridd_reusable_block ) {
			echo wp_kses_post( do_blocks( $gridd_reusable_block->post_content ) );
		}
		?>
	</div>
</div>

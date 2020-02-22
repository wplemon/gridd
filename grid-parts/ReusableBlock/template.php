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
?>

<div <?php Theme::print_attributes( $attributes, "reusable_block_$gridd_reusable_block_id" ); ?>>
	<div class="inner">
		<?php

		// Edit link.
		Blog::get_the_edit_link( $gridd_reusable_block_id, true );

		// The block.
		$gridd_reusable_block = get_post( $gridd_reusable_block_id );
		if ( $gridd_reusable_block ) {
			/**
			 * Note to reviewer:
			 *
			 * The way this is structured is similar to what WP-Core does.
			 * Using wp_kses_post inside the contents here is safe.
			 */
			echo do_blocks( wp_kses_post( $gridd_reusable_block->post_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
	</div>
</div>

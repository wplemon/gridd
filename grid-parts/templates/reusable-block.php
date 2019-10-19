<?php
/**
 * Template part for the Reusable_Block.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Reusable_Block;
use Gridd\Style;
use Gridd\Theme;
use Gridd\Blog;

$style = Style::get_instance( "grid-part/navigation/$gridd_reusable_block_id" );

// Add main styles.
$style->add_file( get_theme_file_path( 'grid-parts/styles/reusable-block/styles.min.css' ) );

// Replace ID with $gridd_reusable_block_id.
$style->replace( 'ID', $gridd_reusable_block_id );
?>

<div <?php Theme::print_attributes( [ 'class' => "gridd-tp gridd-tp-reusable-block gridd-tp-reusable_block_$gridd_reusable_block_id" ], "reusable_block_$gridd_reusable_block_id" ); ?>>
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( "gridd-inline-css-reusable-block-$gridd_reusable_block_id" );
	?>
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

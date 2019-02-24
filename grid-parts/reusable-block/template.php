<?php
/**
 * Template part for the Reusable_Block.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Reusable_Block;
use Gridd\Style;
use Gridd\AMP;

$gridd_reusable_block_id = (int) get_theme_mod( "gridd_grid_reusable_block_{$id}_id", 0 );
if ( ! $gridd_reusable_block_id ) {
	return;
}

$style = Style::get_instance( "grid-part/navigation/$id" );

// Add main styles.
$style->add_file( get_theme_file_path( 'grid-parts/reusable-block/styles/styles.min.css' ) );

// Replace ID with $id.
$style->replace( 'ID', $id );

// Add vars to replace.
$style->add_vars(
	[
		"--gridd-reusable-block-$id-bg"      => get_theme_mod( "gridd_grid_reusable_block_{$id}_bg_color", '#ffffff' ),
		"--gridd-reusable-block-$id-color"   => get_theme_mod( "gridd_grid_reusable_block_{$id}_color", 1 ),
		"--gridd-reusable-block-$id-padding" => get_theme_mod( "gridd_grid_reusable_block_{$id}_padding", '1em' ),
	]
);

?>
<div class="gridd-tp gridd-tp-reusable-block gridd-tp-reusable_block_<?php echo absint( $id ); ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( "gridd-inline-css-reusable-block-$id" );
	?>
	<div class="inner">
		<?php
		$gridd_reusable_block = get_post( $gridd_reusable_block_id );
		if ( $gridd_reusable_block ) {
			echo wp_kses_post( do_blocks( $gridd_reusable_block->post_content ) );
		}
		?>
	</div>
</div>

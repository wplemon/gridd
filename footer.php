<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gridd
 */

use Gridd\Grid;
use Gridd\Grid_Parts;
use Gridd\AMP;
?>

		</main>
	</div>
	<?php
	/**
	 * Add grid parts below the content.
	 */
	$active_parts     = Grid_Parts::get_instance()->get_active();
	$content_position = array_search( 'content', $active_parts, true );
	if ( false === $content_position ) {
		return;
	}
	foreach ( $active_parts as $key => $val ) {
		if ( $key > $content_position ) {
			do_action( 'gridd_the_grid_part', $val );
		}
	}
	?>
</div>

<?php wp_footer(); ?>
</body>
</html>

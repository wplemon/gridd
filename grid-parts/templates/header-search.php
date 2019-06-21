<?php
/**
 * Template part for the Header Search
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Header;
use Gridd\Style;
use Gridd\Theme;
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-header_search' ], 'wrapper-header_search' ); ?>>
	<?php
	/**
	 * Print styles.
	 */
	Style::get_instance( 'grid-part/header/search' )
		->add_file( get_theme_file_path( 'grid-parts/styles/header/styles-header-search.min.css' ) )
		->the_css( 'gridd-inline-css-header-search' );
	?>

	<div class="gridd-header-search inner" style="display:flex;align-items:center;width:100%;">
		<?php get_search_form( true ); ?>
	</div>
</div>

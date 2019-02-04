<?php
/**
 * Template part for the Header Search
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Header;
use Gridd\Style;

$style = Style::get_instance( 'grid-part/header/search' );
$style->add_string( '.gridd-header-search{background-color:var(--gridd-header-search-bg);padding-left:var(--gridd-header-search-padding-left);padding-right: var(--gridd-header-search-padding-right);}' );
$style->add_string( '.gridd-header-search form.search-form{font-size:var(--gridd-header-search-font-size);color:var(--gridd-header-search-color);}' );
$style->add_string( '.gridd-header-search form.search-form>label>input.search-field{border-bottom:none;}' );
$padding = get_theme_mod(
	'gridd_grid_part_details_header_search_padding',
	[
		'left'  => '1em',
		'right' => '1em',
	]
);
$style->add_vars(
	[
		'--gridd-header-search-bg'            => get_theme_mod( 'gridd_grid_part_details_header_bg_color', '#ffffff' ),
		'--gridd-header-search-padding-left'  => $padding['left'],
		'--gridd-header-search-padding-right' => $padding['right'],
		'--gridd-header-search-font-size'     => get_theme_mod( 'gridd_grid_part_details_header_search_font_size', 1 ) . 'em',
		'--gridd-header-search-color'         => get_theme_mod( 'gridd_grid_part_details_header_search_color', '#000000' ),
	]
);
?>

<div class="gridd-tp gridd-tp-header_search">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-header-search' );
	?>

	<div class="gridd-header-search inner" style="display:flex;align-items:center;width:100%;">
		<?php get_search_form( true ); ?>
	</div>
</div>

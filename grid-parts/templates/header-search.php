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
?>

<div <?php Theme::print_attributes( [ 'class' => 'gridd-tp gridd-tp-header_search' ], 'wrapper-header_search' ); ?>>
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

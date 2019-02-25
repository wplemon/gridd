<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gridd
 */

use Gridd\Grid;
use Gridd\Style;
use Gridd\Grid_Part\Content;
use Gridd\Grid_Parts;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php if ( is_singular() && pings_open() ) : // Add a pingback url auto-discovery header for singularly identifiable articles. ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gridd' ); ?></a>
<?php
/**
 * Add the main grid styles.
 *
 * @since 1.0
 */
$style = Style::get_instance( 'main-grid' );
$style->add_string(
	Grid::get_styles_responsive(
		[
			'context'    => 'main',
			'large'      => Grid::get_options( 'gridd_grid' ),
			'breakpoint' => get_theme_mod( 'gridd_mobile_breakpoint', '992px' ),
			'selector'   => '.gridd-site-wrapper',
			'prefix'     => false,
		]
	)
);
?>
<div id="page" class="site gridd-site-wrapper">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-main-grid' );
	?>
	<?php
	/**
	 * Add grid parts above the content.
	 */
	$active_parts     = Grid_Parts::get_instance()->get_active();
	$content_position = array_search( 'content', $active_parts, true );
	if ( false !== $content_position ) {
		foreach ( $active_parts as $key => $val ) {
			if ( $key < $content_position ) {
				do_action( 'gridd_the_grid_part', $val );
			}
		}
	}
	?>
	<div id="content" class="site-content gridd-tp gridd-tp-content">
		<?php
		/**
		 * Add styles for the content.
		 *
		 * @since 1.0
		 */
		Content::print_styles();
		?>
		<main id="main" class="site-main inner">

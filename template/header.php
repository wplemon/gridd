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
<?php do_action( 'wp_body_open' ); ?>
<progress id="gridd-progress-indicator" value="0"></progress>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gridd' ); ?></a>
<?php
/**
 * Add the main grid styles.
 *
 * @since 1.0
 */
\Gridd\CSS::add_string(
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
<div id="content-width-calc-helper"></div>
<div id="page" class="site gridd-site-wrapper">
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
	<div id="content" class="site-content gridd-tp gridd-tp-content<?php echo get_theme_mod( 'content_custom_options' ) ? ' custom-options' : ''; ?>">
		<main id="main" class="site-main inner">

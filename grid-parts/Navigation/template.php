<?php
/**
 * Template part for the Navigation.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Navigation;
use Gridd\Theme;

\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Navigation/styles.min.css' ) );
\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/nav.min.css' ) );

$responsive_mode = get_theme_mod( "nav_{$id}_responsive_behavior", 'desktop-normal mobile-icon' );
$is_vertical_nav = get_theme_mod( "nav_{$id}_vertical", false );

if ( $is_vertical_nav || false !== strpos( $responsive_mode, 'icon' ) ) {
	\Gridd\CSS::add_file( get_theme_file_path( 'assets/css/nav-vertical.min.css' ) );
}

$wrapper_class  = "gridd-tp gridd-tp-nav gridd-tp-nav_$id";
$wrapper_class .= ' gridd-menu-collapse-position-' . get_theme_mod( "nav_{$id}_expand_icon_position", 'center-right' );

if ( get_theme_mod( "nav_{$id}_custom_options", false ) ) {
	$wrapper_class .= ' custom-options';
}

if ( false !== strpos( $responsive_mode, 'mobile-hidden' ) ) {
	$wrapper_class .= ' hide-on-small';
}

$responsive_mode_parts = explode( ' ', $responsive_mode );
foreach ( $responsive_mode_parts as $responsive_mode_part ) {
	$wrapper_class .= ' gridd-' . $responsive_mode_part;
}
?>

<div <?php Theme::print_attributes( [ 'class' => $wrapper_class ], "wrapper-nav_$id" ); ?>>
	<div class="inner">
		<?php if ( false !== strpos( $responsive_mode, 'icon' ) ) : ?>
			<?php
			$toggle_classes = [ 'gridd-toggle-navigation' ];
			/**
			 * Add the toggle button.
			 */
			$icons = Navigation::get_expand_svgs();
			$icon  = get_theme_mod( "nav_{$id}_expand_icon", 'menu-1' );
			$label = trim( get_theme_mod( "nav_{$id}_expand_label", 'MENU' ) );
			if ( ! empty( $label ) ) {
				$label = '<span class="gridd-menu-toggle-label">' . $label . '</span><span style="display:block;width:1em;"></span>';
			}
			/**
			 * Prints the button.
			 * No need to escape this, there's zero user input.
			 * Everything is hardcoded and things that need escaping
			 * are properly escaped in the function itself.
			 */
			echo Theme::get_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput
				[
					'context'                      => [ 'expand-navigation' ],
					'expanded_state_id'            => "navMenuExpanded{$id}",
					'expanded'                     => 'false',
					'screen_reader_label_collapse' => __( 'Collapse Navigation', 'gridd' ),
					'screen_reader_label_expand'   => __( 'Expand Navigation', 'gridd' ),
					'screen_reader_label_toggle'   => __( 'Toggle Navigation', 'gridd' ),
					'label'                        => $label . '<span style="position"relative;"><span class="icon open">' . $icons[ $icon ] . '</span><span class="icon close">' . Navigation::get_collapse_svg( $icon ) . '</span></span>',
					'classes'                      => $toggle_classes,
				]
			);
			?>
		<?php endif; ?>
		<nav
		<?php
		$classes  = 'navigation gridd-nav-' . ( $is_vertical_nav ? 'vertical' : 'horizontal' );
		$classes .= ( ! has_nav_menu( "menu-$id" ) ) ? ' no-menu' : '';
		Theme::print_attributes(
			[
				'id'            => "navigation-$id",
				'class'         => $classes,
				'aria-expanded' => 'false',
			],
			"navigation-$id"
		);
		?>
		>
			<?php

			// Print the menu.
			wp_nav_menu(
				[
					'theme_location' => "menu-$id",
					'menu_id'        => "nav-$id",
					'item_spacing'   => 'discard',
				]
			);
			?>
		</nav>
	</div>
</div>

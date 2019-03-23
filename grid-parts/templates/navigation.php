<?php
/**
 * Template part for the Navigation.
 *
 * @package Gridd
 * @since 1.0
 */

use Gridd\Grid_Part\Navigation;
use Gridd\Style;
use Gridd\AMP;
use Gridd\Theme;

$style = Style::get_instance( "grid-part/navigation/$id" );

// Add main styles.
$style->add_string( Navigation::get_global_styles() );
$style->add_file( get_theme_file_path( 'grid-parts/styles/navigation/styles.min.css' ) );

$responsive_mode       = get_theme_mod( "gridd_grid_nav_{$id}_responsive_behavior", 'desktop-normal mobile-hidden' );
$added_vertical_styles = false;

// Check if we need to add desktop styles.
if ( false !== strpos( $responsive_mode, 'desktop-normal' ) ) {

	// Check if we need to add the vertical styles.
	if ( get_theme_mod( "gridd_grid_nav_{$id}_vertical", false ) ) {
		$style->add_file( get_theme_file_path( 'grid-parts/styles/navigation/styles-vertical.min.css' ) );
		$added_vertical_styles = true;
	}
}

// Add collapsed styles if needed.
if ( false !== strpos( $responsive_mode, 'icon' ) ) {

	// Add the media-query.
	if ( false !== strpos( $responsive_mode, 'desktop-normal' ) ) {
		$style->add_string( '@media only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){' );
	}

	// Add styles.
	$style->add_file( get_theme_file_path( 'grid-parts/styles/navigation/styles-collapsed.min.css' ) );
	if ( ! $added_vertical_styles ) {
		$style->add_file( get_theme_file_path( 'grid-parts/styles/navigation/styles-vertical.min.css' ) );
	}

	// Close the media-query.
	if ( false !== strpos( $responsive_mode, 'desktop-normal' ) ) {
		$style->add_string( '}' );
	}
}

// Hide on mobile.
if ( false !== strpos( $responsive_mode, 'mobile-hidden' ) ) {
	$style->add_string( '@media only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){.gridd-tp-nav_' . $id . '.gridd-mobile-hidden{display:none;}}' );
}

// Replace ID with $id.
$style->replace( 'ID', $id );

// Add vars to replace.
$style->add_vars(
	[
		"--gridd-nav-$id-bg"                  => get_theme_mod( "gridd_grid_nav_{$id}_bg_color", '#ffffff' ),
		"--gridd-nav-$id-font-size"           => get_theme_mod( "gridd_grid_nav_{$id}_font_size", 1 ) . 'em',
		"--gridd-nav-$id-padding"             => get_theme_mod( "gridd_grid_nav_{$id}_padding", '1em' ),
		"--gridd-nav-$id-justify"             => get_theme_mod( "gridd_grid_nav_{$id}_justify_content", 'center' ),
		"--gridd-nav-$id-accent-color"        => get_theme_mod( "gridd_grid_nav_{$id}_accent_color", '#ffffff' ),
		"--gridd-nav-$id-accent-bg"           => get_theme_mod( "gridd_grid_nav_{$id}_accent_bg_color", '#0f5e97' ),
		"--gridd-nav-$id-color"               => get_theme_mod( "gridd_grid_nav_{$id}_items_color", '#000000' ),
		"--gridd-nav-$id-collapsed-icon-size" => get_theme_mod( "gridd_grid_nav_{$id}_collapse_icon_size", 1 ) . 'em',
		"--gridd-nav-$id-submenu-bg"          => get_theme_mod( "gridd_grid_nav_{$id}_bg_color", '#ffffff' ),
	]
);

if ( class_exists( 'ariColor' ) ) {
	$color = \ariColor::newColor( get_theme_mod( "gridd_grid_nav_{$id}_bg_color", '#ffffff' ) )->getNew( 'alpha', 1 )->toCSS( 'hex' );
	if ( ! is_customize_preview() ) {
		$style->add_string( ":root{--gridd-nav-$id-submenu-bg:$color;}" );
	}
	$style->add_vars(
		[
			"--gridd-nav-$id-submenu-bg" => $color,
		]
	);
}

$wrapper_class  = "gridd-tp gridd-tp-nav_$id";
$wrapper_class .= ' gridd-menu-collapse-position-' . get_theme_mod( "gridd_grid_nav_{$id}_expand_icon_position", 'center-right' );

$responsive_mode_parts = explode( ' ', $responsive_mode );
foreach ( $responsive_mode_parts as $responsive_mode_part ) {
	$wrapper_class .= ' gridd-' . $responsive_mode_part;
}
$wrapper_class = apply_filters( 'gridd_grid_part_class', $wrapper_class, "gridd-tp-nav_$id" );
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-navigation-' . $id );
	?>
	<div class="inner">
		<?php if ( false !== strpos( $responsive_mode, 'icon' ) ) : ?>
			<?php
			$toggle_classes = [ 'gridd-toggle-navigation' ];
			if ( get_theme_mod( "gridd_grid_nav_{$id}_expand_icon_boxed", false ) ) {
				$toggle_classes[] = 'gridd-nav-toggle-boxed';
			}
			/**
			 * Add the toggle button.
			 */
			$icons = Navigation::get_expand_svgs();
			$icon  = get_theme_mod( "gridd_grid_nav_{$id}_expand_icon", 'menu-1' );
			$label = trim( get_theme_mod( "gridd_grid_nav_{$id}_expand_label", 'MENU' ) );
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
					'expanded_state_id'            => 'navMenuExpanded' . absint( $id ),
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
			id="navigation-<?php echo absint( $id ); ?>"
			class="navigation gridd-nav-<?php echo ( get_theme_mod( "gridd_grid_nav_{$id}_vertical", false ) ) ? 'vertical' : 'horizontal'; ?>"
			role="navigation"
			aria-expanded="false"
			<?php if ( AMP::is_active() ) : ?>
				<?php
				/**
				 * We use AMP so we need to output AMP-specific markup.
				 *
				 * @link https://amp-wp.org/documentation/playbooks/toggling-hamburger-menus/
				 */
				?>
				[class]="'navigation gridd-nav-<?php echo ( get_theme_mod( "gridd_grid_nav_{$id}_vertical", false ) ) ? 'vertical' : 'horizontal'; ?>' + ( navMenuExpanded<?php echo absint( $id ); ?> ? ' active' : '' )"
				[aria-expanded]="navMenuExpanded<?php echo absint( $id ); ?> ? 'true' : 'false'"
			<?php endif; ?>
		>

			<?php if ( has_nav_menu( "menu-$id" ) ) : ?>
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
			<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
				<div style="background:#FFEBEE;border:1px solid #EF9A9A;color:#B71C1C;font-size:14px;padding:12px;text-align:center;">
					<?php
					/* translators: The navigation number. */
					printf( esc_html__( 'Please assign a menu to the "Navigation %d" menu.', 'gridd' ), absint( $id ) );
					?>
				</div>
			<?php endif; ?>
		</nav>
	</div>
</div>

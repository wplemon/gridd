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

$style = Style::get_instance( "grid-part/navigation/$id" );

// Add main styles.
$style->add_string( Navigation::get_global_styles() );
$style->add_file( get_theme_file_path( 'grid-parts/navigation/styles/main.min.css' ) );

$added_vertical_styles = false;

// Check if we need to add desktop styles.
if ( 'always' !== get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ) ) {

	// Check if we need to add the vertical styles.
	if ( get_theme_mod( "gridd_grid_nav_{$id}_vertical", false ) ) {
		$style->add_file( get_theme_file_path( 'grid-parts/navigation/styles/vertical.min.css' ) );
		$added_vertical_styles = true;
	}
}

// Add collapsed styles if needed.
if ( 'never' !== get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ) ) {

	// Add the media-query.
	if ( 'mobile' === get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ) ) {
		$style->add_string( '@media only screen and (max-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '800px' ) . '){' );
	}

	// Add styles.
	$style->add_file( get_theme_file_path( 'grid-parts/navigation/styles/collapsed.min.css' ) );
	if ( ! $added_vertical_styles ) {
		$style->add_file( get_theme_file_path( 'grid-parts/navigation/styles/vertical.min.css' ) );
	}

	// Close the media-query.
	if ( 'mobile' === get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ) ) {
		$style->add_string( '}' );
	}
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
	$style->add_vars(
		[
			"--gridd-nav-$id-submenu-bg" => \ariColor::newColor( get_theme_mod( "gridd_grid_nav_{$id}_bg_color", '#ffffff' ) )->getNew( 'alpha', 1 )->toCSS( 'hex' ),
		]
	);
}
$classes = [
	'gridd-tp',
	"gridd-tp-nav_$id",
	'gridd-menu-collapse-' . get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ),
	'gridd-menu-collapse-position-' . get_theme_mod( "gridd_grid_nav_{$id}_expand_icon_position", 'center-right' ),
];
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php
	/**
	 * Print styles.
	 */
	$style->the_css( 'gridd-inline-css-navigation-' . $id );
	?>
	<div class="inner">
		<?php if ( 'never' !== get_theme_mod( "gridd_grid_nav_{$id}_collapse_to_icon", 'mobile' ) ) : ?>
			<?php
			/**
			 * Add the toggle button.
			 */
			$icons = Navigation::get_expand_svgs();
			$icon  = get_theme_mod( "gridd_grid_nav_{$id}_expand_icon", 'plus-5' );

			/**
			 * Prints the button.
			 * No need to escape this, it's already escaped in the function itself.
			 */
			echo gridd_toggle_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				[
					'expanded_state_id'            => 'navMenuExpanded' . absint( $id ),
					'expanded'                     => 'false',
					'screen_reader_label_collapse' => __( 'Collapse Navigation', 'gridd' ),
					'screen_reader_label_expand'   => __( 'Expand Navigation', 'gridd' ),
					'screen_reader_label_toggle'   => __( 'Toggle Navigation', 'gridd' ),
					'label'                        => '<span class="icon open">' . $icons[ $icon ] . '</span><span class="icon close">' . Navigation::get_collapse_svg( $icon ) . '</span>',
					'classes'                      => [ 'gridd-toggle-navigation' ],
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
						'depth'          => '3',
					]
				);
				?>
			<?php else : ?>
				<?php
				/* translators: The navigation number. */
				printf( esc_html__( 'Please assign a menu to the "Navigation %d" menu.', 'gridd' ), absint( $id ) );
				?>
			<?php endif; ?>
		</nav>
	</div>
</div>

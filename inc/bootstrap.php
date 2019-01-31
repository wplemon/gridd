<?php
/**
 * Bootstraps the Gridd theme.
 * We're using this file instead of functions.php to avoid fatal errors on PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gridd
 */

use Gridd\Admin;
use Gridd\EDD;
use Gridd\AMP;
use Gridd\Widget_Output_Filters;

/**
 * Require the main theme class.
 *
 * @since 1.0
 */
require_once __DIR__ . '/gridd.php';

/**
 * If Kirki isn't loaded as a plugin, load the included version.
 */
if ( ! class_exists( 'Kirki' ) ) {
	require_once __DIR__ . '/kirki/kirki.php';
}

/**
 * The Gridd Autoloader.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
	function( $class ) {
		$name = strtolower( str_replace( '_', '-', substr( $class, 5 ) ) );
		if ( 0 === strpos( $class, 'Gridd' ) ) {
			$path = __DIR__ . str_replace( '\\', '/', "$name/$name" ) . '.php';
			$path = str_replace( '//', '/', $path );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
			$path = __DIR__ . str_replace( '\\', '/', $name ) . '.php';
			$path = str_replace( '//', '/', $path );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}
);

// Add the widget filters.
Widget_Output_Filters::get_instance();

/**
 * The theme version.
 *
 * @since 1.0
 */
define( 'GRIDD_VERSION', '1.0' );

/**
 * Load the textdomain.
 *
 * @since 1.0
 */
function gridd_load_theme_textdomain() {
	load_theme_textdomain( 'gridd', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'gridd_load_theme_textdomain' );

/**
 * Load admin screen.
 *
 * @since 1.0
 */
new Admin();

/**
 * Load the main theme class.
 *
 * @since 1.0
 * @return Gridd
 */
function gridd() {
	return Gridd::get_instance();
}

/**
 * Customizer additions.
 */
require __DIR__ . '/customize.php';

/**
 * Load EDD mods.
 *
 * @since 1.0
 */
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	new EDD();
}

/**
 * Post-edit link.
 *
 * @since 1.0
 * @return void
 */
function gridd_the_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of the post.*/
			esc_html__( 'Edit %s', 'gridd' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Comments link.
 *
 * @since 1.0
 * @return void
 */
function gridd_the_comments_link() {
	comments_popup_link(
		sprintf(
			/* translators: %s: post title */
			'<span class="screen-reader-text">' . esc_html__( 'Leave a Comment on %s', 'gridd' ) . '</span>',
			get_the_title()
		)
	);
}

/**
 * Adds support for wp.com-specific theme functions.
 *
 * @global array $themecolors
 */
function gridd_wpcom_setup() {
	global $themecolors;

	// Set theme colors for third party services.
	if ( ! isset( $themecolors ) ) {
		$themecolors = [
			'bg'     => '',
			'border' => '',
			'text'   => '',
			'link'   => '',
			'url'    => '',
		];
	}
}
add_action( 'after_setup_theme', 'gridd_wpcom_setup' );

/**
 * Move categories counts inside the links.
 *
 * @since 1.0
 * @param string $html The links.
 * @return string
 */
function gridd_filter_wp_list_categories( $html ) {
	return str_replace(
		[ '</a> (', ')' ],
		[ ' (', ')</a>' ],
		$html
	);
}
add_filter( 'wp_list_categories', 'gridd_filter_wp_list_categories' );

/**
 * Add classes to the comment-form submit button.
 *
 * @since 1.0
 * @param array $args The comment-form args.
 * @return array
 */
function gridd_filter_comment_form_defaults( $args ) {
	$args['class_submit'] .= ' wp-block-button__link';
	return $args;
}

/**
 * Returns an array of the singular parts for post, pages & CPTs.
 *
 * @since 1.0
 * @return array
 */
function gridd_get_post_parts() {
	return apply_filters(
		'gridd_get_post_parts',
		get_theme_mod( 'gridd_post_parts', [ 'post-title', 'post-date-author', 'post-thumbnail', 'post-content', 'post-category', 'post-tags', 'post-comments-link' ] )
	);
}

/**
 * Integrates WPBakery Builder in the theme.
 *
 * @since 1.0
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
	add_action( 'vc_before_init', 'vc_set_as_theme' );
}

/**
 * Remove logo.
 *
 * @since 1.0
 */
add_filter( 'vc_nav_front_logo', '__return_empty_string' );

/**
 * Get an array of settings we don't want to save in templates.
 *
 * @return array
 */
function gridd_get_template_theme_mods_blacklist() {
	$blacklist = [
		'nav_menu_locations',
		'nav_menu',
		'add_menu',
		'nav_menu_item',
		'gridd_logo_focus_on_core_section',
		'gridd_footer_focus_on_core_widgets_section',
		'gridd_logo_focus_on_typography_section',
		'gridd_logo_focus_on_sidebar-1_section',
		'gridd_logo_focus_on_sidebar-2_section',
		'gridd_logo_focus_on_sidebar-3_section',
		'gridd_logo_focus_on_sidebar-4_section',
		'gridd_logo_focus_on_sidebar-5_section',
		'gridd_logo_focus_on_sidebar-6_section',
		'gridd_logo_focus_on_sidebar-7_section',
		'gridd_logo_focus_on_sidebar-8_section',
		'gridd_logo_focus_on_sidebar-9_section',
		'gridd_logo_focus_on_sidebar-10_section',
	];
	return apply_filters( 'gridd_template_blacklist_settings', $blacklist );
}

// Init AMP Support.
new AMP();

/**
 * Generates the HTML for a toggle button.
 *
 * @param array $args The button arguments.
 * @since 1.0
 */
function gridd_toggle_button( $args ) {

	$html = '';

	if ( AMP::is_active() ) {

		// Create new state for managing storing the whether the sub-menu is expanded.
		$html .= '<amp-state id="' . esc_attr( $args['expanded_state_id'] ) . '">';
		$html .= '<script type="application/json">' . $args['expanded'] . '</script>';
		$html .= '</amp-state>';
	}

	if ( ! isset( $args['classes'] ) ) {
		$args['classes'] = [];
	}
	$args['classes'][] = 'gridd-toggle';
	$classes           = implode( ' ', array_unique( $args['classes'] ) );

	$button_atts = [
		'aria-expanded' => 'false',
	];

	if ( AMP::is_active() ) {
		$button_atts['[class]']         = $classes . '+(' . $args['expanded_state_id'] . '?\'toggled-on\':\'\')';
		$button_atts['[aria-expanded]'] = "{$args['expanded_state_id']} ? 'true' : 'false'";
		$button_atts['on']              = "tap:AMP.setState({ {$args['expanded_state_id']}: ! {$args['expanded_state_id']} })";
	}

	/*
	 * Create the toggle button which mutates the state and which has class and
	 * aria-expanded attributes which react to the state changes.
	 */
	$html .= '<button class="' . $classes . '"';
	foreach ( $button_atts as $key => $val ) {
		if ( ! empty( $key ) ) {
			$html .= ' ' . $key . '="' . $val . '"';
		}
	}
	$html .= '>';

	if ( AMP::is_active() && isset( $args['screen_reader_label_collapse'] ) && isset( $args['screen_reader_label_expand'] ) ) {

		// Let the screen reader text in the button also update based on the expanded state.
		$html .= '<span class="screen-reader-text"';
		$html .= ' [text]="' . $args['expanded_state_id'] . '?\'' . esc_attr( $args['screen_reader_label_collapse'] ) . '\':\'' . esc_attr( $args['screen_reader_label_expand'] ) . '\'">';
		$html .= esc_html( $args['screen_reader_label_expand'] );
	} elseif ( isset( $args['screen_reader_label_toggle'] ) ) {
		$html .= '<span class="screen-reader-text">' . $args['screen_reader_label_toggle'] . '</span>';
	}
	$html .= '</span>';
	$html .= $args['label'];
	$html .= '</button>';

	return $html;
}

add_filter(
	'wp_nav_menu_args',
	function( $args ) {
		$args['menu_class'] .= ' gridd-navigation';
		return $args;
	}
);

/**
 * Utility function to get the contents of a non-executable file as plain text.
 *
 * @since 1.0
 * @param string $path     The file path.
 * @param bool   $absolute Set to true if we have an absolute path instead of relative to the theme root.
 * @return string          The file contents or empty string if no file was found.
 */
function gridd_get_file_contents( $path, $absolute = false ) {
	ob_start();
	if ( $absolute && file_exists( $path ) ) {
		include $path;
	} else {
		include locate_template( $path, false, false );
	}
	return ob_get_clean();
}

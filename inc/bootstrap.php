<?php
/**
 * Bootstraps the Gridd theme.
 * We're using this file instead of functions.php to avoid fatal errors on PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gridd
 */

use Gridd\Gridd;
use Gridd\Admin;
use Gridd\EDD;
use Gridd\AMP;
use Gridd\Widget_Output_Filters;

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
		$prefix   = 'Gridd\\';
		$base_dir = __DIR__ . '/classes/';

		$len = strlen( $prefix );
		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}
		$relative_class = substr( $class, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Hookable get_template_part() function.
 * Allows us to get templates from a plugin or any other path using custom hooks.
 *
 * @since 1.0.3
 * @param string $slug The template slug.
 * @param string $name The template name.
 * @see https://developer.wordpress.org/reference/functions/get_template_part/
 * @return void
 */
function gridd_get_template_part( $slug, $name = null ) {
	$custom_path = false;
	/**
	 * Determine if we want to use a custom path for this template-part.
	 *
	 * @since 1.0.3
	 * @param string|false $custom_path The custom template-part path. Defaults to false. Use absolute path.
	 * @param string       $slug        The template slug.
	 * @param string       $name        The template name.
	 * @return string|false
	 */
	$custom_path = apply_filters( 'gridd_get_template_part', $custom_path, $slug, $name );
	if ( $custom_path ) {
		if ( file_exists( $custom_path ) ) {
			include $custom_path;
		}
		return;
	}
	// Get the default template part.
	get_template_part( $slug, $name );
}

// Add the widget filters.
Widget_Output_Filters::get_instance();

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
 * Load admin tweaks.
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
		[ 'post-title', 'post-date-author', 'post-thumbnail', 'post-content', 'post-category', 'post-tags', 'post-comments-link' ]
	);
}

/**
 * Integrates WPBakery Builder in the theme.
 *
 * @since 1.0
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
	add_action( 'vc_before_init', 'vc_set_as_theme' );
	add_filter( 'vc_nav_front_logo', '__return_empty_string' );
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
		$button_atts['[class]']         = '(' . $args['expanded_state_id'] . '?\'' . $classes . ' toggled-on\':\'' . $classes . '\')';
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

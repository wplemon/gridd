<?php
/**
 * REST API Implementation.
 *
 * @package Gridd
 * @since 1.1
 *
 * phpcs:ignoreFile WordPress.Files.FileName.InvalidClassFileName
 */

namespace Gridd;

use Gridd\Grid_Part\Sidebar;

/**
 * Implements the custom REST Routes.
 *
 * @since 1.1
 */
class Rest {

	/**
	 * An array of available partials.
	 *
	 * @static
	 * @access private
	 * @since 1.1
	 * @var array
	 */
	private static $partials = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		if ( AMP::is_active() ) {
			return;
		}
		add_action( 'wp_footer', [ $this, 'script' ], PHP_INT_MAX );
		add_filter( 'gridd_rest_api_partials_choices', [ $this, 'partials_choices' ] );
	}

	/**
	 * Registers a partial and makes it available to the REST API.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @param array $args The partial arguments.
	 * @return void
	 */
	public static function register_partial( $args ) {
		self::$partials[ $args['id'] ] = $args['label'];
	}

	/**
	 * Adds partials choices to the multiselect option.
	 *
	 * @access public
	 * @since 1.1
	 * @param array $choices Existing choices
	 * @return array
	 */
	public function partials_choices( $choices ) {
		return $choices;
	}

	/**
	 * Get an array of all available partials.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @return array
	 */
	public static function get_all_partials() {
		return self::$partials;
	}

	/**
	 * Get an array of partials we want to defer.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @return array
	 */
	public static function get_partials() {
		return get_theme_mod( 'gridd_rest_api_partials', self::get_deferred_defaults() );
	}

	/**
	 * Get an array of partials we want to defer by default.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @return array
	 */
	public static function get_deferred_defaults() {
		$partials     = [ 'footer', 'nav-handheld' ];
		$max_sidebars = Sidebar::get_number_of_sidebars();
		for ( $i = 1; $i <= $max_sidebars; $i++ ) {
			$partials[] = "sidebar_$i";
		}
		return $partials;
	}

	/**
	 * Check if a partial is deferred or not.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @param string $id The partial ID.
	 * @return bool
	 */
	public static function is_partial_deferred( $id ) {
		if ( AMP::is_active() ) {
			return false;
		}
		return ( \in_array( $id, self::get_partials(), true ) );
	}

	/**
	 * Print the script in the footer.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function script() {
		$partials = wp_json_encode( self::get_partials() );
		$route    = esc_url_raw( site_url( '?rest_route=/gridd/v1/partials/' ) );
		echo '<script>';
		echo 'var griddRestParts=' . $partials . ',griddRestRoute="' . $route . '";';
		include get_theme_file_path( 'assets/js/rest-partials.min.js' );
		echo '</script>';
	}
}

<?php // phpcs:ignore WordPress.Files.FileName
/**
 * REST API Implementation.
 *
 * @package Gridd
 * @since 1.1
 */

namespace Gridd;

use Gridd\Grid_Part\Sidebar;
use Gridd\AMP;

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
		if ( apply_filters( 'gridd_disable_rest', false ) ) {
			return;
		}
		add_action( 'wp_footer', [ $this, 'add_assets' ], PHP_INT_MAX );
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
	 * @param array $choices Existing choices.
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
		return [];
		/**
		 * Disable deferred parts.
		return get_theme_mod( 'gridd_rest_api_partials', [] );
		*/
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
		if ( AMP::is_active() || \is_customize_preview() ) {
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
	public function add_assets() {
		$partials = wp_json_encode( self::get_partials() );
		if ( ! empty( $partials ) ) {

			// Add script.
			$route = esc_url_raw( site_url( '?rest_route=/gridd/v1/partials/' ) );
			echo '<script>';
			// No need to escape this, it's a JSON-encoded array of hardcoded values.
			echo 'var griddRestParts=' . $partials . ',griddRestRoute="' . $route . '";'; // phpcs:ignore WordPress.Security.EscapeOutput
			include get_theme_file_path( 'assets/js/rest-partials.min.js' );
			echo '</script>';

			// Add style.
			echo '<style>';
			include get_theme_file_path( 'assets/css/core/skeleton.min.css' );
			echo '</style>';
		}
	}
}

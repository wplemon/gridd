<?php
/**
 * A utility class for Kirki.
 *
 * @package     Kirki
 * @category    Core
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license    https://opensource.org/licenses/MIT
 * @since       3.0.9
 */

namespace Kirki\Core;

use Kirki\Core\Values;

/**
 * Utility class.
 */
class Util {

	/**
	 * Constructor.
	 *
	 * @since 3.0.9
	 * @access public
	 */
	public function __construct() {
		add_filter( 'http_request_args', [ $this, 'http_request' ], 10, 2 );
	}

	/**
	 * Determine if Kirki is installed as a plugin.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_plugin() {
		$is_plugin = false;
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
		}

		// Get all plugins.
		$plugins = get_plugins();
		$_plugin = '';
		foreach ( $plugins as $plugin => $args ) {
			if ( ! $is_plugin && isset( $args['Name'] ) && ( 'Kirki' === $args['Name'] || 'Kirki Toolkit' === $args['Name'] ) ) {
				$is_plugin = true;
				$_plugin   = $plugin;
			}
		}

		// No need to proceed any further if Kirki wasn't found in the list of plugins.
		if ( ! $is_plugin ) {
			return false;
		}

		// Make sure the is_plugins_loaded function is loaded.
		include_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

		// Extra logic in case the plugin is installed but not activated.
		if ( $_plugin && is_plugin_inactive( $_plugin ) ) {
			return false;
		}
		return $is_plugin;
	}

	/**
	 * Build the variables.
	 *
	 * @static
	 * @access public
	 * @since 3.0.9
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {

		$variables = [];

		// Loop through all fields.
		foreach ( Kirki::$fields as $field ) {

			// Check if we have variables for this field.
			if ( isset( $field['variables'] ) && $field['variables'] && ! empty( $field['variables'] ) ) {

				// Loop through the array of variables.
				foreach ( $field['variables'] as $field_variable ) {

					// Is the variable ['name'] defined? If yes, then we can proceed.
					if ( isset( $field_variable['name'] ) ) {

						// Do we have a callback function defined? If not then set $variable_callback to false.
						$variable_callback = ( isset( $field_variable['callback'] ) && is_callable( $field_variable['callback'] ) ) ? $field_variable['callback'] : false;

						// If we have a variable_callback defined then get the value of the option
						// and run it through the callback function.
						// If no callback is defined (false) then just get the value.
						$variables[ $field_variable['name'] ] = Values::get_value( $field['settings'] );
						if ( $variable_callback ) {
							$variables[ $field_variable['name'] ] = call_user_func( $field_variable['callback'], Values::get_value( $field['settings'] ) );
						}
					}
				}
			}
		}

		// Pass the variables through a filter ('kirki_variable') and return the array of variables.
		return apply_filters( 'kirki_variable', $variables );
	}

	/**
	 * HTTP Request injection.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array  $request The request params.
	 * @param string $url     The request URL.
	 * @return array
	 */
	public function http_request( $request = [], $url = '' ) {

		// Early exit if installed as a plugin or not a request to wordpress.org,
		// or finally if we don't have everything we need.
		if (
			self::is_plugin() ||
			false === strpos( $url, 'wordpress.org' ) || (
				! isset( $request['body'] ) ||
				! isset( $request['body']['plugins'] ) ||
				! isset( $request['body']['translations'] ) ||
				! isset( $request['body']['locale'] ) ||
				! isset( $request['body']['all'] )
			)
		) {
			return $request;
		}

		$plugins = json_decode( $request['body']['plugins'], true );
		if ( ! isset( $plugins['plugins'] ) ) {
			return $request;
		}
		$exists = false;
		foreach ( $plugins['plugins'] as $plugin ) {
			if ( isset( $plugin['Name'] ) && 'Kirki Toolkit' === $plugin['Name'] ) {
				$exists = true;
			}
		}
		// Inject data.
		if ( ! $exists && defined( 'KIRKI_PLUGIN_FILE' ) ) {
			$plugins['plugins']['kirki/kirki.php'] = get_plugin_data( KIRKI_PLUGIN_FILE );
		}
		$request['body']['plugins'] = wp_json_encode( $plugins );
		return $request;
	}

	/**
	 * Returns the $wp_version.
	 *
	 * @static
	 * @access public
	 * @since 3.0.12
	 * @param string $context Use 'minor' or 'major'.
	 * @return int|string      Returns integer when getting the 'major' version.
	 *                         Returns string when getting the 'minor' version.
	 */
	public static function get_wp_version( $context = 'minor' ) {
		global $wp_version;

		// We only need the major version.
		if ( 'major' === $context ) {
			$version_parts = explode( '.', $wp_version );
			return $version_parts[0];
		}

		return $wp_version;
	}
}

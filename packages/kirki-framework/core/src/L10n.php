<?php
/**
 * Internationalization helper.
 *
 * @package     Kirki
 * @category    Core
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Core;

use Kirki\Core\Util;

/**
 * Handles translations
 */
class L10n {

	/**
	 * The plugin textdomain
	 *
	 * @access private
	 * @var string
	 */
	private $textdomain = 'kirki';

	/**
	 * The theme textdomain
	 *
	 * @access private
	 * @var string
	 */
	private $theme_textdomain = '';

	/**
	 * The class constructor.
	 * Adds actions & filters to handle the rest of the methods.
	 *
	 * @access public
	 */
	public function __construct() {

		// If Kirki is installed as a plugin, load the texdomain.
		if ( Util::is_plugin() ) {
			add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
			return;
		}

		// If we got this far, then Kirki is embedded in a plugin.
		// We want the theme's textdomain to handle translations.
		add_filter( 'override_load_textdomain', [ $this, 'override_load_textdomain' ], 5, 3 );
	}

	/**
	 * Load the plugin textdomain
	 *
	 * @access public
	 */
	public function load_textdomain() {
		if ( null !== $this->get_path() ) {
			load_textdomain( $this->textdomain, $this->get_path() );
		}
		load_plugin_textdomain( $this->textdomain, false, dirname( KIRKI_PLUGIN_FILE ) . '/languages' );
	}

	/**
	 * Gets the path to a translation file.
	 *
	 * @access protected
	 * @return string Absolute path to the translation file.
	 */
	protected function get_path() {
		$path_found = false;
		$found_path = null;
		foreach ( $this->get_paths() as $path ) {
			if ( $path_found ) {
				continue;
			}
			$path = wp_normalize_path( $path );
			if ( file_exists( $path ) ) {
				$path_found = true;
				$found_path = $path;
			}
		}
		return $found_path;
	}

	/**
	 * Returns an array of paths where translation files may be located.
	 *
	 * @access protected
	 * @return array
	 */
	protected function get_paths() {
		return [
			WP_LANG_DIR . '/' . $this->textdomain . '-' . get_locale() . '.mo',
			dirname( KIRKI_PLUGIN_FILE ) . '/languages/' . $this->textdomain . '-' . get_locale() . '.mo',
		];
	}

	/**
	 * Allows overriding the "kirki" textdomain from a theme.
	 *
	 * @since 3.0.12
	 * @access public
	 * @param bool   $override Whether to override the .mo file loading. Default false.
	 * @param string $domain   Text domain. Unique identifier for retrieving translated strings.
	 * @param string $mofile   Path to the MO file.
	 * @return bool
	 */
	public function override_load_textdomain( $override, $domain, $mofile ) {
		global $l10n;
		if ( isset( $l10n[ $this->get_theme_textdomain() ] ) ) {
			$l10n['kirki'] = $l10n[ $this->get_theme_textdomain() ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}

		// Check if the domain is "kirki".
		if ( 'kirki' === $domain ) {
			return true;
		}
		return $override;
	}

	/**
	 * Get the theme's textdomain.
	 *
	 * @since 3.0.12
	 * @access private
	 * @return string
	 */
	private function get_theme_textdomain() {
		if ( '' === $this->theme_textdomain ) {

			// Get the textdomain.
			$theme                  = wp_get_theme();
			$this->theme_textdomain = $theme->get( 'TextDomain' );

			// If no texdomain was found, use the template folder name.
			if ( ! $this->theme_textdomain ) {
				$this->theme_textdomain = get_template();
			}
		}
		return $this->theme_textdomain;
	}
}

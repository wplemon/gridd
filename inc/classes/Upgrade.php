<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Handle theme-options changes on upgrades.
 *
 * Sometimes on a new version we introduce new options,
 * deprecate old ones, tweak default values and so on.
 * This object is an attempt to avoid backwards-compatibility issues
 * by providing an automated upgrade process for theme-mods.
 *
 * @since 1.1.18
 * @package gridd
 */

namespace Gridd;

/**
 * The Upgrader object.
 *
 * @since 1.1.18
 */
class Upgrade {

	/**
	 * The theme-slug.
	 *
	 * @since 1.1.18
	 */
	const THEME_SLUG = 'gridd';

	/**
	 * The option-name containing the theme-version saved in the database.
	 *
	 * @since 1.1.18
	 */
	const OPTION_NAME_VER = 'gridd_version';

	/**
	 * An array of versions that contain upgrades.
	 *
	 * @access private
	 * @since 1.1.18
	 * @var array
	 */
	private $versions_upgrades = [
		'3.0.0',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1.18
	 */
	public function __construct() {

		// Maybe run the updates?
		$this->maybe_run_upgrades();
	}

	/**
	 * Runs a check to see if we should run the upgrades.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return void
	 */
	private function maybe_run_upgrades() {

		// Get the lest version saved in the database.
		$last_version = get_option( self::OPTION_NAME_VER );

		// If the option is not set, this is a fresh installation.
		// Set the version option and early exit.
		if ( ! $last_version ) {
			update_option( self::OPTION_NAME_VER, GRIDD_VERSION );
			return;
		}

		// If the saved version is the current version, early exit.
		if ( GRIDD_VERSION === $last_version ) {
			return;
		}

		// If we got this far, this is an update.
		// Loop versions that need an update and trigger what's needed.
		foreach ( $this->versions_upgrades as $version ) {

			// Check version against last saved.
			if ( ! version_compare( $version, $last_version ) > 0 ) {
				continue;
			}

			// We need to trigger update for this version.
			$this->run_version_upgrade( $version );
		}
	}

	/**
	 * Runs upgrades for a specific version.
	 *
	 * @access private
	 * @since 1.1.18
	 * @param string $version The version we want to run upgrades for.
	 * @return void
	 */
	private function run_version_upgrade( $version ) {
		$class_name = '\Gridd\Upgrades\Version_' . str_replace( '.', '_', $version );
		new $class_name();
		update_option( self::OPTION_NAME_VER, GRIDD_VERSION );
	}
}

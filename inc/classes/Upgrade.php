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
	 * The option-name used to determine if this is a theme-update.
	 *
	 * @since 1.1.18
	 */
	const OPTION_NAME_UPGRADE_FROM = 'gridd_update_from';

	/**
	 * The option-name containing the theme-version saved in the database.
	 *
	 * @since 1.1.18
	 */
	const OPTION_NAME_VER = 'gridd_version';

	/**
	 * Determine if this is a theme-update or not.
	 * If not, then it's a fresh installation.
	 *
	 * @access private
	 * @since 1.1.18
	 * @var bool
	 */
	private $is_upgrade = false;

	/**
	 * The previous theme version.
	 *
	 * @access private
	 * @since 1.1.18
	 * @var string
	 */
	private $old_version;


	/**
	 * An array of versions that contain upgrades.
	 *
	 * @access private
	 * @since 1.1.18
	 * @var array
	 */
	private $versions_upgrades = [
		'1.1.18',
		'1.1.19',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1.18
	 */
	public function __construct() {
		// Hook in the updater.
		add_action( 'upgrader_process_complete', [ $this, 'upgrader_process_complete' ], 10, 2 );
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
		if ( $this->is_upgrade() ) {
			$this->run_upgrades();
		}
	}

	/**
	 * Runs the upgrades we need.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return void
	 */
	private function run_upgrades() {
		$old_version = $this->get_old_version();

		// Run updates for all versions applicable.
		foreach ( $this->versions_upgrades as $version ) {
			if ( version_compare( $version, $old_version ) >= 0 ) {
				$this->run_version_upgrade( $version );
			}
		}

		// Finish the upgrade.
		$this->finish_upgrade();
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
	}

	/**
	 * Fires when the upgrader process is complete.
	 *
	 * See also {@see 'upgrader_package_options'}.
	 *
	 * @access public
	 * @since 1.1.18
	 *
	 * @param WP_Upgrader $upgrader WP_Upgrader instance. In other contexts, $this, might be a
	 *                              Theme_Upgrader, Plugin_Upgrader, Core_Upgrade, or Language_Pack_Upgrader instance.
	 * @param array       $hook_extra {
	 *     Array of bulk item update data.
	 *
	 *     @type string $action       Type of action. Default 'update'.
	 *     @type string $type         Type of update process. Accepts 'plugin', 'theme', 'translation', or 'core'.
	 *     @type bool   $bulk         Whether the update process is a bulk update. Default true.
	 *     @type array  $plugins      Array of the basename paths of the plugins' main files.
	 *     @type array  $themes       The theme slugs.
	 *     @type array  $translations {
	 *         Array of translations update data.
	 *
	 *         @type string $language The locale the translation is for.
	 *         @type string $type     Type of translation. Accepts 'plugin', 'theme', or 'core'.
	 *         @type string $slug     Text domain the translation is for. The slug of a theme/plugin or
	 *                                'default' for core translations.
	 *         @type string $version  The version of a theme, plugin, or core.
	 *     }
	 * }
	 */
	public function upgrader_process_complete( $upgrader, $hook_extra ) {

		// Sanity check.
		if ( ! is_object( $upgrader ) || ! isset( $upgrader->result ) ) {
			return;
		}

		// Make sure this is an update for the gridd theme.
		if ( ! is_array( $upgrader->result ) || ! isset( $upgrader->result['destination_name'] ) || self::THEME_SLUG !== $upgrader->result['destination_name'] ) {
			return;
		}

		/**
		 * If we got this far then the theme just got updated.
		 * We need to get the previous version and save it in an option
		 * so we can run the update scripts (if any exist) on the next request.
		 */
		if ( isset( $upgrader->skin ) && isset( $upgrader->skin->theme_info ) ) {
			update_option( self::OPTION_NAME_UPGRADE_FROM, $upgrader->skin->theme_info->display( 'Version' ) );
		}
	}

	/**
	 * Determine if this is a theme update or a fresh installation.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return bool
	 */
	private function is_upgrade() {
		$update_from  = get_option( self::OPTION_NAME_UPGRADE_FROM );
		$last_version = get_option( self::OPTION_NAME_VER );

		if ( ! $update_from && $last_version && version_compare( GRIDD_VERSION, $last_version ) > 0 ) {
			update_option( self::OPTION_NAME_UPGRADE_FROM, $last_version );
			$update_from = $last_version;
		}

		return ( $update_from || ! $last_version );
	}

	/**
	 * Get the old theme version.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return string
	 */
	private function get_old_version() {
		$old_version = get_option( self::OPTION_NAME_UPGRADE_FROM );
		/**
		 * Option was introduced in v1.1.18. If the option doesn't exist
		 * then the previous theme-version is 1.1.17 or below.
		 */
		return $old_version ? $old_version : '1.1.17';
	}

	/**
	 * Cleanup after the update process has finished.
	 *
	 * @access private
	 * @since 1.1.18
	 * @return void
	 */
	private function finish_upgrade() {
		// Delete upgrade option.
		delete_option( self::OPTION_NAME_UPGRADE_FROM );
		// Update version option.
		update_option( self::OPTION_NAME_VER, GRIDD_VERSION );
	}
}

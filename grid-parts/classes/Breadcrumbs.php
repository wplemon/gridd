<?php
/**
 * Gridd Breadcrumbs grid-part
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
use Gridd\Grid_Part;
use Gridd\Grid_Parts;

/**
 * The Gridd\Grid_Part\Breadcrumbs object.
 *
 * @since 1.0
 */
class Breadcrumbs extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'breadcrumbs';

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		spl_autoload_register( [ $this, 'autoloader' ] );
		add_action( 'after_setup_theme', [ $this, 'load_breadcrumbs_textdomain' ] );
		add_filter( 'override_load_textdomain', [ $this, 'override_load_textdomain' ], 10, 2 );
		add_action( 'init', [ $this, 'remove_woocommerce_breadcrumbs' ] );
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
	}

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_html__( 'Breadcrumbs', 'gridd' ),
			'color'    => [ '#B39DDB', '#000' ],
			'priority' => 60,
			'hidden'   => false,
			'id'       => $this->id,
		];
	}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( $this->id === $part ) {
			Theme::get_template_part( 'grid-parts/templates/breadcrumbs' );
		}
	}

	/**
	 * Autoloader for hybrid-breadcrumbs.
	 *
	 * @param string $class The fully-qualified class name.
	 * @return void
	 */
	private function autoloader( $class ) {

		// Does the class use the namespace prefix?
		if ( 0 !== strncmp( 'Hybrid\\Breadcrumbs\\', $class, 19 ) ) {
			// No, move to the next registered autoloader.
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class, 19 );

		// Replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php.
		$file = \get_template_directory() . '/inc/hybrid-breadcrumbs/src/' . str_replace( '\\', '/', $relative_class ) . '.php';

		// if the file exists, require it.
		if ( file_exists( $file ) ) {
			require $file;
		}
	}

	/**
	 * Loads additional textdomains.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function load_breadcrumbs_textdomain() {

		// Load the framework textdomain.
		load_textdomain( 'gridd', '' );
	}

	/**
	 * Overrides the textdomain to force-use the one from the theme.
	 *
	 * @access public
	 * @since 1.0
	 * @param bool   $override Whether to override the .mo file loading. Default false.
	 * @param string $domain   Text domain. Unique identifier for retrieving translated strings.
	 * @return bool
	 */
	public function override_load_textdomain( $override, $domain ) {

		// Check if the domain is our framework domain.
		if ( 'gridd' === $domain ) {
			global $l10n;

			// If the theme's textdomain is loaded, assign the theme's translations to the framework's textdomain.
			if ( isset( $l10n['gridd'] ) ) {
				$l10n[ $domain ] = $l10n['gridd']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
			}

			// Always override.  We only want the theme to handle translations.
			$override = true;
		}
		return $override;
	}

	/**
	 * Remove Woocommerce breadcrumbs.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function remove_woocommerce_breadcrumbs() {
		if ( Grid_Parts::is_grid_part_active( 'breadcrumbs' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb' );
		}
	}
}

new Breadcrumbs;

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

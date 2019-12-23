<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd Breadcrumbs grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Theme;
use Gridd\Grid_Part;
use Gridd\Grid_Parts;
use Gridd\Style;

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
		add_action( 'after_setup_theme', [ $this, 'load_breadcrumbs_textdomain' ] );
		add_filter( 'override_load_textdomain', [ $this, 'override_load_textdomain' ], 10, 2 );
		add_action( 'woocommerce_before_main_content', [ $this, 'remove_woocommerce_breadcrumbs' ], 1 );
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
		if ( $this->id === $part && apply_filters( 'gridd_render_grid_part', true, 'breadcrumbs' ) ) {
			Theme::get_template_part( 'grid-parts/Breadcrumbs/template' );
			/**
			 * Print styles.
			 */
			Style::get_instance( 'grid-part/breadcrumbs' )
				->add_file( __DIR__ . '/styles.min.css' )
				->the_css( 'gridd-inline-css-breadcrumbs' );
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
				$l10n[ $domain ] = $l10n['gridd']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
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

new Breadcrumbs();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

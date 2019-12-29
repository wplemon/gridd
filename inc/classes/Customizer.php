<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Extra bits and pieces needed for the customizer implementation.
 *
 * @package Gridd
 */

namespace Gridd;

use Gridd\Grid_Parts;

/**
 * Extra methods and actions for the customizer.
 *
 * @since 1.0
 */
class Customizer {

	/**
	 * An array of grid controls.
	 *
	 * @static
	 * @since 1.0
	 * @var array
	 */
	public static $grid_controls = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'kirki_gridd_css_skip_hidden', '__return_false' );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'customize_controls_print_scripts', [ $this, 'extra_customizer_scripts' ], 9999 );
		add_action( 'customize_preview_init', [ $this, 'preview_customizer_scripts' ] );
		add_action( 'after_setup_theme', [ $this, 'setup_grid_filters' ] );
		add_filter( 'kirki_control_types', [ $this, 'kirki_control_types' ] );
	}

	/**
	 * Enqueue related scripts & styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		// Enqueue the script and style.
		wp_enqueue_script( 'dragselect', get_template_directory_uri() . '/assets/vendor/dragselect/ds.min.js', [ 'jquery' ], '1.9.1', false );
		wp_enqueue_script( 'gridd-set-setting-value', get_template_directory_uri() . '/assets/js/customizer-gridd-set-setting-value.js', [ 'jquery', 'customize-base' ], GRIDD_VERSION, false );
	}

	/**
	 * Setup yhe grid filters.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function setup_grid_filters() {
		foreach ( array_keys( self::$grid_controls ) as $id ) {
			add_filter( 'pre_set_theme_mod_' . $id, [ $this, 'theme_mod_gridd_grid' ] );
		}
	}

	/**
	 * Filters the theme mod value on save.
	 *
	 * @since 1.0
	 * @param string $value The new value of the theme mod.
	 */
	public function theme_mod_gridd_grid( $value ) {
		if ( is_string( $value ) && json_decode( $value, true ) ) {
			$value = json_decode( $value, true );
		}
		return $value;
	}

	/**
	 * Adds extra customizer scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function extra_customizer_scripts() {
		wp_enqueue_script( 'gridd-customizer-script', get_template_directory_uri() . '/assets/js/customizer.js', [ 'jquery', 'customize-base' ], GRIDD_VERSION, false );
		wp_localize_script(
			'gridd-customizer-script',
			'griddTemplatePreviewScript',
			[
				'nonce'       => wp_create_nonce( 'gridd-template-preview' ),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nestedGrids' => Grid_Parts::get_instance()->get_grids(),
				'l10n'        => [
					'headerImageDescription' => esc_html__( 'Choose a background image for your header. Please note that the image will only be visible if the grid parts in your header use transparent colors, or if you are using a grid-gap for your header grid.', 'gridd' ),
				],
			]
		);
	}

	/**
	 * Adds customizer scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function preview_customizer_scripts() {
		wp_enqueue_script( 'gridd-customizer-preview-script', get_theme_file_uri( '/assets/js/customizer-preview.js' ), [ 'jquery', 'customize-preview', 'jquery-color' ], time(), true );
	}

	/**
	 * Gets the control description.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @deprecated 3.0.0
	 */
	public static function get_control_description() {}

	/**
	 * This is only kept here for backwards-compatibility
	 * in order to avoid PHP errors in case a child theme uses this method.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @deprecated 1.1.16
	 */
	public static function section_description() {}

	/**
	 * Proxy function for Kirki.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @deprecated 3.0.0
	 * @param string $id   The section ID.
	 * @param array  $args The field arguments.
	 * @return void
	 */
	public static function add_section( $id, $args ) {
		\Kirki::add_section( $id, $args );
	}

	/**
	 * Proxy function for Kirki.
	 * Adds an outer section.
	 *
	 * @static
	 * @access public
	 * @since 1.0.3
	 * @deprecated 3.0.0
	 * @return void
	 */
	public static function add_outer_section() {}

	/**
	 * Proxy function for Kirki.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param array $args The field arguments.
	 * @return void
	 */
	public static function add_field( $args ) {
		$args = apply_filters( 'gridd_field_args', $args );

		\Kirki::add_field( 'gridd', $args );
		if ( 'gridd_grid' === $args['type'] ) {
			self::$grid_controls[ $args['settings'] ] = $args;
		}
	}

	/**
	 * Register our custom control type with Kirki.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $controls An array of Kirki controls along with their classes.
	 * @return array
	 */
	public function kirki_control_types( $controls ) {
		$controls['gridd_grid'] = '\Gridd\Customizer\Control\Grid';
		return $controls;
	}

	/**
	 * Gets an array of all grid-parts along with their sections.
	 *
	 * @static
	 * @access public
	 * @since 2.0.0
	 * @deprecated 3.0.0
	 */
	public static function get_grid_parts_sections() {}

	/**
	 * Check if a grid-part is active.
	 *
	 * Used as an active_callback for sections & controls.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @param string $id The grid-part ID.
	 * @return bool
	 */
	public static function is_section_active_part( $id ) {
		// Check the main grid.
		if ( \Gridd\Grid_Parts::is_grid_part_active( $id, 'gridd_grid' ) ) {
			return true;
		}

		// Check sub-grids.
		$grids = \Gridd\Grid_Parts::get_instance()->get_grids();
		foreach ( $grids as $grid ) {
			if ( \Gridd\Grid_Parts::is_grid_part_active( $id, $grid ) ) {
				return true;
			}
		}
		return false;
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

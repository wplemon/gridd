<?php
/**
 * Extra bits and pieces needed for the customizer implementation.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd;
use Gridd\Grid_Parts;
use Gridd\Section_Plus;

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
		add_action( 'customize_controls_print_styles', [ $this, 'customize_controls_print_styles' ] );
		add_action( 'customize_controls_print_scripts', [ $this, 'extra_customizer_scripts' ], 9999 );
		add_action( 'customize_preview_init', [ $this, 'preview_customizer_scripts' ] );
		add_action( 'after_setup_theme', [ $this, 'setup_grid_filters' ] );

		if ( ! Gridd::is_pro() ) {
			add_action( 'customize_controls_enqueue_scripts', [ $this, 'plus_section_scripts' ], 0 );
			add_action( 'customize_register', [ $this, 'plus_section' ], 99 );
		}
	}

	/**
	 * Enqueue related scripts & styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		// Enqueue the script and style.
		wp_enqueue_script( 'dragselect', get_template_directory_uri() . '/assets/vendor/dragselect/ds.min.js', [ 'jquery' ], '1.9.1', false );
		wp_enqueue_script( 'gridd-set-setting-value', get_template_directory_uri() . '/assets/js/customizer/gridd-set-setting-value.js', [ 'jquery', 'customize-base' ], GRIDD_VERSION, false );
	}

	/**
	 * Enqueue related scripts & styles for the plus section.
	 *
	 * @access public
	 */
	public function plus_section_scripts() {
		wp_enqueue_script( 'gridd-plus-section', get_template_directory_uri() . '/assets/js/customizer/plus-section.js', [ 'customize-controls' ], GRIDD_VERSION, false );
		wp_enqueue_style( 'gridd-plus-section', get_template_directory_uri() . '/assets/css/customizer/plus-section.css', [], GRIDD_VERSION );
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
	 * Sanitize the gridd_grid setting.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $value The control value.
	 * @return array       The value - sanitized.
	 */
	public function sanitize_gridd_grid( $value ) {

		// If string, json-decode first.
		if ( is_string( $value ) ) {
			$value = json_decode( $value, true );
		}

		// Areas.
		$areas   = [];
		$max_col = 1;
		$max_row = 1;
		if ( isset( $value['areas'] ) && is_array( $value['areas'] ) ) {
			foreach ( $value['areas'] as $area_key => $area_value ) {
				$area_key = sanitize_key( $area_key );
				if ( isset( $area_value['cells'] ) && is_array( $area_value['cells'] ) ) {
					$areas[ $area_key ] = [
						'cells' => [],
					];
					foreach ( $area_value['cells'] as $cell ) {

						// Sanitize row & column numbers.
						$row = absint( $cell[0] );
						$col = absint( $cell[1] );

						// Set the array.
						$areas[ $area_key ]['cells'][] = [ $row, $col ];

						// Calculate max-col & max-row.
						$max_row = max( $row, $max_row );
						$max_col = max( $col, $max_col );
					}
				}
			}
		}
		$value['areas'] = $areas;

		// Rows.
		$rows          = [];
		$value['rows'] = max( $max_row, absint( $value['rows'] ) );
		for ( $i = 0; $i <= $value['rows']; $i++ ) {
			$rows[ $i ] = 'auto';
			if ( isset( $value['gridTemplate']['rows'][ $i ] ) ) {
				$rows[ $i ] = esc_attr( $value['gridTemplate']['rows'][ $i ] );
			}
		}
		$value['gridTemplate']['rows'] = $rows;

		// Columns.
		$columns          = [];
		$value['columns'] = max( $max_col, absint( $value['columns'] ) );
		for ( $i = 0; $i <= $value['columns']; $i++ ) {
			$columns[ $i ] = 'auto';
			if ( isset( $value['gridTemplate']['columns'][ $i ] ) ) {
				$columns[ $i ] = esc_attr( $value['gridTemplate']['columns'][ $i ] );
			}
		}
		$value['gridTemplate']['columns'] = $columns;

		return $value;
	}

	/**
	 * Adds custom styles to the customizer.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function customize_controls_print_styles() {
		echo '<style id="gridd-customizer-styles">';
		include get_template_directory() . '/assets/css/customizer/customizer.css';
		echo '</style>';
	}

	/**
	 * Adds extra customizer scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function extra_customizer_scripts() {
		wp_enqueue_script( 'gridd-customizer-script', get_template_directory_uri() . '/assets/js/customizer/customizer.js', [ 'jquery', 'customize-base' ], GRIDD_VERSION, false );
		wp_localize_script(
			'gridd-customizer-script',
			'griddTemplatePreviewScript',
			[
				'nonce'       => wp_create_nonce( 'gridd-template-preview' ),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nestedGrids' => Grid_Parts::get_instance()->get_grids(),
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
		wp_enqueue_script( 'gridd-customizer-preview-script', get_theme_file_uri( '/assets/js/customizer.js' ), [ 'jquery', 'customize-preview', 'jquery-color' ], time(), true );
	}

	/**
	 * Gets text strings that are reused multiple times in the customizer.
	 * This is useful because in many controls the descriptions andtooltips are identical.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $context The tooltip context.
	 * @param array  $params  Extra parameters. Used in sprintf().
	 * @return string
	 */
	public function get_text( $context, $params = [] ) {
		switch ( $context ) {
			case 'padding':
				return sprintf(
					/* translators: link with the text "this article on the Mozilla CSS-reference docs". */
					esc_html__( 'For details on how padding works, please refer to %s.', 'gridd' ),
					'<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank">' . esc_html__( 'this article on the Mozilla CSS-reference docs', 'gridd' ) . '</a>'
				);

			case 'related-font-size':
				return esc_html__( 'The font-size defined here is in relation to the global font-size defined in your typography options.', 'gridd' );

			case 'grid-part-max-width':
				return sprintf(
					/* translators: %1$s, %2$s, %3$s are all examples of valid CSS values. */
					esc_html__( 'The maximum width that the contents of this grid-part can use. Use any valid CSS value like %1$s, %2$s or %3$s.', 'gridd' ),
					'<code>50em</code>',
					'<code>800px</code>',
					'<code>100%</code>'
				);

			case 'grid-gap-description':
				return esc_html__( 'Adds a gap between your grid-parts, both horizontally and vertically. This can be particularly useful if you want them to look separate.', 'gridd' );

			case 'grid-gap-tooltip':
				return esc_html__( 'If you have a background-color defined for this grid, then that color will be visible through these gaps which creates a unique appearance since each grid-part looks separate.', 'gridd' );

			case 'vertical-alignment':
				return esc_html__( 'If the container for this grid-part is taller than its contents, you can use this option to vertically align the contents inside theie parent container.', 'gridd' );

			case 'sticky-description':
				return esc_html__( 'If set to "On", this grid-part will stick to the top of the page when users scroll-down.', 'gridd' );

			case 'sticky-tooltip':
				return __( 'Please make sure there is only 1 sticky part in your templates. Using multiple sticky elements may lead to unexpected results if used improperly. <a href="#" target="_blank">Read this article</a> for more details on how the sticky feature works and how to properly use it on your website', 'gridd' );

			case 'a11y-textcolor-description':
				return esc_html__( 'Select from a list of colors that ensure accessibility compliance with the selected background color. Colors on the top provide more contrast than colors lower in the list.', 'gridd' );

			case 'a11y-textcolor-tooltip':
				return sprintf(

					/* translators: The link properties. */
					__( 'Want to learn more about how these colors are selected based on the chosen background color? <a %1$s>Read this post</a>.', 'gridd' ),
					'href="https://wplemon.com" target="_blank"'
				);

			default:
				return '';
		}
	}

	/**
	 * Sets up the customizer section for upgrade-to-plus feature.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param WP_Customize_Manager $manager The WP_Customize_Manager object.
	 * @return void
	 */
	public function plus_section( $manager ) {
		if ( Gridd::is_pro() ) {
			return;
		}
		require_once 'section-plus.php';
		// Register custom section type.
		$manager->register_section_type( 'Gridd\Section_Plus' );

		// Register section.
		$manager->add_section(
			new Section_Plus(
				$manager,
				'gridd_plus',
				[
					'title'        => esc_html__( 'Gridd Plus', 'gridd' ),
					'button_label' => esc_html__( 'Get Plus', 'gridd' ),
					'button_url'   => 'https://wplemon.com/gridd',
					'priority'     => -999,
				]
			)
		);
	}
}

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
	 * An array of background to text color setting automations.
	 *
	 * @static
	 * @since 1.0
	 * @var array
	 */
	public static $auto_text_color = [
		'gridd_grid_content_background_color'                          => 'gridd_text_color',
		'gridd_grid_breadcrumbs_background_color'                      => 'gridd_grid_breadcrumbs_color',
		'gridd_grid_part_details_header_contact_info_background_color' => 'gridd_grid_part_details_header_contact_info_text_color',
		'gridd_grid_footer_copyright_bg_color]'                        => 'gridd_grid_footer_copyright_color',
		'gridd_grid_footer_sidebar_1_bg_color'                         => 'gridd_grid_footer_sidebar_1_color',
		'gridd_grid_footer_sidebar_2_bg_color'                         => 'gridd_grid_footer_sidebar_2_color',
		'gridd_grid_footer_sidebar_3_bg_color'                         => 'gridd_grid_footer_sidebar_3_color',
		'gridd_grid_footer_sidebar_4_bg_color'                         => 'gridd_grid_footer_sidebar_4_color',
		'gridd_grid_footer_sidebar_5_bg_color'                         => 'gridd_grid_footer_sidebar_5_color',
		'gridd_grid_footer_sidebar_6_bg_color'                         => 'gridd_grid_footer_sidebar_6_color',
		'gridd_grid_nav_1_bg_color'                                    => 'gridd_grid_nav_1_items_color',
		'gridd_grid_nav_2_bg_color'                                    => 'gridd_grid_nav_2_items_color',
		'gridd_grid_nav_3_bg_color'                                    => 'gridd_grid_nav_3_items_color',
		'gridd_grid_nav_4_bg_color'                                    => 'gridd_grid_nav_4_items_color',
		'gridd_grid_nav_5_bg_color'                                    => 'gridd_grid_nav_5_items_color',
		'gridd_grid_nav_6_bg_color'                                    => 'gridd_grid_nav_6_items_color',
		'gridd_grid_sidebar_1_background_color'                        => 'gridd_grid_sidebar_1_color',
		'gridd_grid_sidebar_2_background_color'                        => 'gridd_grid_sidebar_2_color',
		'gridd_grid_sidebar_3_background_color'                        => 'gridd_grid_sidebar_3_color',
		'gridd_grid_sidebar_4_background_color'                        => 'gridd_grid_sidebar_4_color',
		'gridd_grid_sidebar_5_background_color'                        => 'gridd_grid_sidebar_5_color',
		'gridd_grid_sidebar_6_background_color'                        => 'gridd_grid_sidebar_6_color',
	];

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
		add_action( 'wp_ajax_gridd_minimize_plus_descriptions', array( $this, 'dismiss_customizer_section_descriptions' ) );
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
		foreach ( array_values( self::$auto_text_color ) as $item ) {
			echo ( Gridd::is_plus_active() ) ?: '#customize-control-' . esc_attr( $item ) . '{display:none!important;}';
		}
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
				'nonce'         => wp_create_nonce( 'gridd-template-preview' ),
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nestedGrids'   => Grid_Parts::get_instance()->get_grids(),
				'plusDismissed' => self::is_minimized( 'plus-descriptions' ),
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
		wp_enqueue_script( 'wcag_colors', get_template_directory_uri() . '/assets/js/wcagColors.js', [], '1.0', false );
		wp_enqueue_script( 'gridd-customizer-preview-script', get_theme_file_uri( '/assets/js/customizer.js' ), [ 'jquery', 'customize-preview', 'jquery-color', 'wcag_colors' ], time(), true );
		wp_localize_script(
			'gridd-customizer-preview-script',
			'griddCustomizerVars',
			array(
				'autoText' => self::$auto_text_color,
			)
		);
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
			case 'related-font-size':
				return esc_html__( 'The font-size defined here is in relation to the global font-size defined in your typography options.', 'gridd' );

			case 'grid-gap-tooltip':
				return esc_html__( 'If you have a background-color defined for this grid, then that color will be visible through these gaps which creates a unique appearance since each grid-part looks separate.', 'gridd' );

			case 'vertical-alignment':
				return esc_html__( 'If the container for this grid-part is taller than its contents, you can use this option to vertically align the contents inside theie parent container.', 'gridd' );

			default:
				return '';
		}
	}

	/**
	 * Helper method to avoid writing the same code over and over and over and over again.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $plus The Additional features available in Gridd Plus.
	 * @param string $docs A link to the docs for this section.
	 * @return string      The final HTML.
	 */
	public static function section_description( $plus, $docs ) {
		$html  = '';
		$html .= ( $plus || $docs ) ? '<div class="gridd-section-description">' : '';
		if ( $plus && ! Gridd::is_plus_active() ) {
			$button = '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss', 'gridd' ) . '</span></button>';
			$html  .= '<div class="gridd-go-plus"><div class="description">' . $plus . '</div>' . $button . '</div>';
		}
		if ( $docs ) {
			$html .= '<div class="gridd-docs"><a href="' . $docs . '" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>';
		}
		$html .= ( $plus || $docs ) ? '</div>' : '';

		return $html;
	}

	/**
	 * Minimize (soft-dismiss) customizer Plus promos in section descriptions & the help links.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function dismiss_customizer_section_descriptions() {
		check_ajax_referer( 'gridd-template-preview', 'security' );
		set_theme_mod( 'gridd_customizer_section_descriptions_minimized', true );
		error_log( print_r( $_POST, true ) );
	}

	/**
	 * Check if we've minimized the plus promos/section.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $context What we want to check (plus|help).
	 * @return bool
	 */
	public static function is_minimized( $context ) {
		return get_theme_mod( 'gridd_customizer_section_descriptions_minimized' );
	}
}

<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Extra bits and pieces needed for the customizer implementation.
 *
 * @package Gridd
 */

namespace Gridd;

use Gridd\Theme;
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
		add_action( 'customize_controls_print_styles', [ $this, 'customize_controls_print_styles' ] );
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
	 * Enqueue related scripts & styles for the plus section.
	 *
	 * @access public
	 */
	public function plus_section_scripts() {
		wp_enqueue_script( 'gridd-plus-section', get_template_directory_uri() . '/assets/js/customizer-plus-section.js', [ 'customize-controls' ], GRIDD_VERSION, false );
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
	 * Adds custom styles to the customizer.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function customize_controls_print_styles() {
		$grid_parts_sections = self::get_grid_parts_sections();
		?>
		<style id="gridd-customizer-styles">
			<?php include get_template_directory() . '/assets/css/customizer/customizer.css'; ?>
			<?php foreach ( self::get_grid_parts_sections() as $section ) : ?>
				#accordion-section-<?php echo esc_attr( $section ); ?> .accordion-section-title {
					/* padding-top: 5px; */
					/* padding-bottom: 4px; */
					/* border-bottom-color: transparent; */
					/* border-bottom-color: #e3e8ee; */
					/* background-color: #f7f9fb; */
					/* color: #999; */
				}
				#accordion-section-<?php echo esc_attr( $section ); ?> .accordion-section-title:after {
					/* content: "\f540" */
					/* content: "\f100" */
					/* content: "\f111" */
					/* content: "\f139" */
				}
				#accordion-section-<?php echo esc_attr( $section ); ?> .accordion-section-title:hover {
					background-color: #e3e8ee;
					/* background-color: #f7f7f7; */
				}
			<?php endforeach; ?>
		}
		</style>
		<?php
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
	 * @param array $args The description parameters.
	 * @return string
	 */
	public static function get_control_description( $args ) {
		$description = '';

		if ( is_array( $args ) ) {
			if ( isset( $args['short'] ) ) {
				$description .= $args['short'];
			}
			if ( isset( $args['details'] ) ) {
				$label = esc_html__( 'Details', 'gridd' );
				if ( isset( $args['label'] ) ) {
					$label = $args['label'];
				}
				$description .= '<details><summary style="color:#0085ba">' . $label . '</summary>';
				$description .= $args['details'];
				$description .= '</details>';
			}
		}
		return $description;
	}

	/**
	 * This is only kept here for backwards-compatibility
	 * in order to avoid PHP errors in case a child theme uses this method.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @deprecated 1.1.16
	 * @param string $id   The section-ID.
	 * @param array  $args The arguments [pro=>[''], docs=>'', tip=>''].
	 * @return string      The final HTML.
	 */
	public static function section_description( $id, $args ) {
		return '';
	}

	/**
	 * Proxy function for Kirki.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $id   The section ID.
	 * @param array  $args The field arguments.
	 * @return void
	 */
	public static function add_panel( $id, $args ) {
		// WIP: Disable icons.
		if ( isset( $args['icon'] ) ) {
			unset( $args['icon'] );
		}
		\Kirki::add_panel( $id, $args );
	}

	/**
	 * Proxy function for Kirki.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $id   The section ID.
	 * @param array  $args The field arguments.
	 * @return void
	 */
	public static function add_section( $id, $args ) {
		// WIP: Disable icons.
		if ( isset( $args['icon'] ) ) {
			unset( $args['icon'] );
		}

		\Kirki::add_section( $id, apply_filters( 'gridd_section_args', $args ) );
	}

	/**
	 * Proxy function for Kirki.
	 * Adds an outer section.
	 *
	 * @static
	 * @access public
	 * @since 1.0.3
	 * @param string $section_id The section ID.
	 * @param array  $args       The field arguments.
	 * @return void
	 */
	public static function add_outer_section( $section_id, $args ) {
		$args['panel'] = 'layout_blocks';
		unset( $args['section'] );
		$args['active_callback'] = function() use ( $section_id ) {
			$grid_parts_sections = self::get_grid_parts_sections();
			$main_grids          = [ 'gridd_grid', 'gridd_header_grid', 'gridd_footer_grid' ];
			$id                  = array_search( $section_id, $grid_parts_sections, true );
			if ( false !== strpos( $section_id, 'grid_part_details' ) && $id ) {
				$is_active = false;
				foreach ( $main_grids as $grid ) {
					if ( \Gridd\Grid_Parts::is_grid_part_active( $id, $grid ) ) {
						$is_active = true;
					}
				}
				return $is_active;
			}

			return true;
		};
		self::add_section( $section_id, $args );
	}

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
	 * @return array
	 */
	public static function get_grid_parts_sections() {
		$sections = [
			'breadcrumbs'         => 'grid_part_details_breadcrumbs',
			'footer_copyright'    => 'grid_part_details_footer_copyright',
			'footer_social_media' => 'grid_part_details_footer_social_media',
			'header_branding'     => 'title_tagline',
			'header_search'       => 'grid_part_details_header_search',
			'header_contact_info' => 'grid_part_details_header_contact_info',
			'social_media'        => 'grid_part_details_social_media',
		];
		/**
		 * These are core but we don't want them in this array.
		 *
		'content'             => 'grid_part_details_content',
		'footer'              => 'grid_part_details_footer',
		'header'              => 'grid_part_details_header',
		'nav-handheld'        => 'gridd_mobile',
		*/

		$nav_nr = \Gridd\Grid_Part\Navigation::get_number_of_nav_menus();
		for ( $i = 1; $i <= $nav_nr; $i++ ) {
			$sections[ "nav_$i" ] = "grid_part_details_nav_$i";
		}

		$reusable_blocks = \Gridd\Grid_Part\ReusableBlock::get_reusable_blocks();
		foreach ( $reusable_blocks as $block ) {
			$sections[ "reusable_block_{$block->ID}" ] = "grid_part_details_reusable_block_{$block->ID}";
		}

		$sidebars_nr = \Gridd\Grid_Part\Sidebar::get_number_of_sidebars();
		for ( $i = 1; $i <= $sidebars_nr; $i++ ) {
			$sections[ "sidebar_$i" ] = "grid_part_details_sidebar_$i";
		}

		$footer_sidebars_nr = \Gridd\Grid_Part\Footer::get_number_of_sidebars();
		for ( $i = 1; $i <= $footer_sidebars_nr; $i++ ) {
			$sections[ "footer_sidebar_$i" ] = "grid_part_details_footer_sidebar_$i";
		}

		return apply_filters( 'gridd_get_grid_parts_sections', $sections );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

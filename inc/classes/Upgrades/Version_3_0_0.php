<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Run upgrades for version 3.0.0
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 3.0.0
 */
class Version_3_0_0 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function run_update() {
		$this->previous_defaults();
		$this->append_custom_css();
		$this->cleanup_theme_mods_names();
		$this->custom_options();
		$this->rename_mods();

		new \Gridd\Upgrades\Block\Header_Contact_Info();
		new \Gridd\Upgrades\Block\Footer_Copyright();
		new \Gridd\Upgrades\Block\Header_Search();
		/**
		 * WIP
		new \Gridd\Upgrades\Block\Widget_Areas();
		 */
	}

	/**
	 * Set previous theme-mod defaults if missing.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function previous_defaults() {
		$old_values = [
			'content_background_color'  => '#ffffff',
			'gridd_grid_header_padding' => '0',
		];

		foreach ( $old_values as $key => $value ) {
			$dummy_value = '[DUMMY VALUE]';
			$saved       = get_theme_mod( $key, $dummy_value );
			if ( $saved === $dummy_value ) {
				set_theme_mod( $key, $value );
			}
		}
	}

	/**
	 * Append custom CSS.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function append_custom_css() {
		$custom_css = wp_get_custom_css();
		$changed    = false;

		// An array of opening and closing tags for auto-added custom CSS for deprecated options.
		$deprecated = [
			'gridd_grid_breadcrumbs_padding'     => [
				'.gridd-tp-breadcrumbs{padding:%s;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Breadcrumbs padding. */',
				'/* End Gridd 3.0 Breadcrumbs padding CSS */',
			],
			'content_padding_top'                => [
				'.gridd-tp-content{padding-top:%sem;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Content padding-top. */',
				'/* End Gridd 3.0 Content padding-top CSS */',
			],
			'content_padding_bottom'             => [
				'.gridd-tp-content{padding-bottom:%sem;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Content padding-bottom. */',
				'/* End Gridd 3.0 Content padding-bottom CSS */',
			],
			'gridd_grid_footer_padding'          => [
				'.gridd-tp-footer .gridd-tp{padding:%s;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Footer padding. */',
				'/* End Gridd 3.0 Footer padding CSS */',
			],
			'gridd_grid_header_branding_padding' => [
				'.gridd-tp-header_branding{padding:%s;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Header-Branding padding. */',
				'/* End Gridd 3.0 Header-Branding padding CSS */',
			],
			'header_contact_info_padding'        => [
				'.gridd-tp-header_contact_info{padding:%s;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Header-Contact-Info padding. */',
				'/* End Gridd 3.0 Header-Contact-Info padding CSS */',
			],
			'header_search_padding'              => [
				'.gridd-tp-header_search{padding:%sem;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Header-Search padding. */',
				'/* End Gridd 3.0 Header-Search padding CSS */',
			],
			'gridd_grid_header_padding'          => [
				'.gridd-tp-header{padding:%s;}',
				'/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Header padding. */',
				'/* End Gridd 3.0 Header padding CSS */',
			],
		];

		$reusable_blocks = \Gridd\Grid_Part\ReusableBlock::get_reusable_blocks();
		if ( $reusable_blocks ) {
			foreach ( $reusable_blocks as $block ) {
				$block_title = get_the_title( $block->ID );
				$deprecated[ "gridd_grid_reusable_block_{$block->ID}_padding" ] = [
					".gridd-tp-reusable_block_{$block->ID}{padding:%s;}",
					"/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Block[$block_title] padding. */",
					"/* End Gridd 3.0 Block[$block_title] padding CSS */",
				];
			}
		}

		$number = \Gridd\Grid_Part\Sidebar::get_number_of_sidebars();
		for ( $i = 1; $i <= $number; $i++ ) {
			$deprecated[ "gridd_grid_sidebar_{$i}_padding" ] = [
				".gridd-tp-sidebar_{$i}{padding:%s;}",
				"/* CSS added via the Gridd v3.0 update to avoid breaking changes for deprecated setting: Sidebar $i padding. */",
				"/* End Gridd 3.0 Sidebar $i padding CSS */",
			];
		}

		foreach ( $deprecated as $setting => $args ) {
			$saved = get_theme_mod( $setting, '[DUMMY VALUE]' );
			if ( '[DUMMY VALUE]' !== $saved && '' !== $saved && '1em' !== $saved && '1' !== $saved && false === strpos( $custom_css, $args[1] ) ) {
				$changed     = true;
				$css         = str_replace( '%s', $saved, $args[0] );
				$custom_css .= "

{$args[1]}
$css
$args[2]";
			}
		}

		if ( $changed ) {
			wp_update_custom_css_post( $custom_css );
		}
	}

	/**
	 * Clean-up theme-mods names.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function cleanup_theme_mods_names() {
		$theme_mods = get_theme_mods();
		if ( $theme_mods && is_array( $theme_mods ) ) {
			foreach ( $theme_mods as $key => $val ) {
				if ( 0 === strpos( $key, 'grid_part_details_' ) || 0 === strpos( $key, 'gridd_grid_' ) ) {
					$new_key = str_replace(
						[ 'grid_part_details_', 'gridd_grid_' ],
						[ '', '' ],
						$key
					);

					if ( ! isset( $theme_mods[ $new_key ] ) ) {
						set_theme_mod( $new_key, $val );
					}
				}
			}
		}
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function custom_options() {
		$custom_options = [
			'breadcrumbs_custom_options',
			'content_custom_options',
			'footer_copyright_custom_options',
			'footer_social_media_custom_options',
			'footer_custom_options',
			'branding_custom_options',
			'header_contact_info_custom_options',
			'header_search_custom_options',
			'header_social_custom_options',
			'header_custom_options',
		];

		foreach ( $custom_options as $name ) {
			set_theme_mod( $name, true );
		}

		$number = \Gridd\Grid_Part\Navigation::get_number_of_nav_menus();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "nav_{$i}_custom_options", true );
		}

		$number = \Gridd\Grid_Part\Footer::get_number_of_sidebars();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "footer_sidebar_{$i}_custom_options", true );
		}

		$number = \Gridd\Grid_Part\Sidebar::get_number_of_sidebars();
		for ( $i = 1; $i <= $number; $i++ ) {
			set_theme_mod( "sidebar_{$i}_custom_options", true );
		}

		$reusable_blocks = \Gridd\Grid_Part\ReusableBlock::get_reusable_blocks();
		if ( $reusable_blocks ) {
			foreach ( $reusable_blocks as $block ) {
				set_theme_mod( "reusable_block_{$block->ID}_custom_options", true );
			}
		}
	}

	/**
	 * Rename specific theme-mods.
	 *
	 * @access private
	 * @since 3.0.0
	 * @return void
	 */
	private function rename_mods() {
		$mods = [
			'gridd_header_grid' => 'header_grid',
			'gridd_footer_grid' => 'footer_grid',
		];

		foreach ( $mods as $old => $new ) {
			$old_val = get_theme_mod( $old, '__UNSET' );
			if ( '__UNSET' !== $old_val ) {
				set_theme_mod( $new, $old_val );
			}
		}
	}
}

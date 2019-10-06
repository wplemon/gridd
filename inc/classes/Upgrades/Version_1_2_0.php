<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Run upgrades for version 1.2.0
 *
 * @since 1.2.0
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The upgrade object.
 *
 * @since 1.2.0
 */
class Version_1_1_19 {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->run_update();
	}

	/**
	 * Runs the update.
	 *
	 * @access private
	 * @since 1.2.0
	 * @return void
	 */
	private function run_update() {
		$this->modified_defaults();
		$this->content_padding();
		$this->rename_theme_mods();
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 1.2.0
	 * @return void
	 */
	private function modified_defaults() {
		$settings_old_defaults = [
			'gridd_grid_content_max_width' => '45em',
			'gridd_grid_header_max_width'  => '45em',
		];
		foreach ( $settings_old_defaults as $setting => $default ) {
			$value = get_theme_mod( $setting );

			if ( ! $value ) {
				set_theme_mod( $setting, $default );
			}
		}
	}

	/**
	 * Handle modified defaults.
	 *
	 * @access private
	 * @since 1.2.0
	 * @return void
	 */
	private function content_padding() {
		$content_padding = get_theme_mod(
			'gridd_grid_content_padding',
			[
				'top'    => '0',
				'bottom' => '0',
				'left'   => '20px',
				'right'  => '20px',
			]
		);
		$top             = $content_padding['top'] ? $content_padding['top'] : '0';
		$bottom          = $content_padding['bottom'] ? $content_padding['bottom'] : '';
		$left            = $content_padding['left'] ? $content_padding['left'] : '20px';
		$right           = $content_padding['right'] ? $content_padding['right'] : '20px';

		// Calculate horizontal padding.
		$left       = ( 5 > intval( $left ) ) ? intval( $left ) : intval( $left ) / 20;
		$right      = ( 5 > intval( $right ) ) ? intval( $right ) : intval( $right ) / 20;
		$horizontal = ( $left + $right ) / 2;
		$horizontal = max( 0, min( $horizontal, 10 ) );
		set_theme_mod( 'content_padding_horizontal', $horizontal );

		// Save top & bottom padding values.
		$top    = ( 5 > intval( $top ) ) ? intval( $top ) : intval( $top ) / 20;
		$top    = max( 0, min( $top, 10 ) );
		$bottom = ( 5 > intval( $bottom ) ) ? intval( $bottom ) : intval( $bottom ) / 20;
		$bottom = max( 0, min( $bottom, 10 ) );
		set_theme_mod( 'content_padding_top', $top );
		set_theme_mod( 'content_padding_bottom', $bottom );
	}

	/**
	 * Handle renamed theme-mods.
	 *
	 * @access private
	 * @since 1.2.0
	 * @return void
	 */
	private function rename_theme_mods() {
		$theme_mods = [
			'gridd_grid_content_max_width'                 => 'content_max_width',
			'gridd_grid_header_max_width'                  => 'header_max_width',
			'gridd_grid_part_details_header_search_mode'   => 'header_search_mode',
			'gridd_grid_part_details_footer_social_icons'  => 'footer_social_icons',
			'gridd_grid_part_details_footer_social_icons_background_color' => 'footer_social_icons_background_color',
			'gridd_grid_part_details_footer_social_icons_icons_color' => 'footer_social_icons_icons_color',
			'gridd_grid_part_details_footer_social_icons_size' => 'footer_social_icons_size',
			'gridd_grid_part_details_footer_social_icons_padding' => 'footer_social_icons_padding',
			'gridd_grid_part_details_footer_social_icons_icons_text_align' => 'footer_social_icons_icons_text_align',
			'gridd_grid_part_details_header_contact_info_background_color' => 'header_contact_info_background_color',
			'gridd_grid_part_details_header_contact_info_text_color' => 'header_contact_info_text_color',
			'gridd_grid_part_details_header_contact_info_font_size' => 'header_contact_info_font_size',
			'gridd_grid_part_details_header_contact_info_padding' => 'header_contact_info_padding',
			'gridd_grid_part_details_header_contact_text_align' => 'header_contact_text_align',
			'gridd_grid_part_details_header_contact_info'  => 'header_contact_info',
			'gridd_grid_part_details_header_bg_color'      => 'header_search_background_color',
			'gridd_grid_part_details_header_search_color'  => 'header_search_color',
			'gridd_grid_part_details_header_search_font_size' => 'header_search_font_size',
			'gridd_grid_part_details_social_icons'         => 'header_social_icons',
			'gridd_grid_part_details_social_icons_background_color' => 'header_social_icons_background_color',
			'gridd_grid_part_details_social_icons_icons_color' => 'header_social_icons_icons_color',
			'gridd_grid_part_details_social_icons_size',
			'header_social_icons_size',
			'gridd_grid_part_details_social_icons_padding' => 'header_social_icons_padding',
			'gridd_grid_part_details_social_icons_icons_text_align' => 'header_social_icons_icons_text_align',
			'gridd_grid_part_details_header_background_color' => 'header_background_color',
			'gridd_grid_part_details_header_parts_background_override' => 'header_parts_background_override',
			'gridd_grid_part_details_footer_parts_background_override' => 'footer_parts_background_override',
		];

		foreach ( $theme_mods as $old => $new ) {
			$old_val = get_theme_mod( $old );
			if ( $old_val ) {
				set_theme_mod( $new, $old_val );
			}
		}
	}
}

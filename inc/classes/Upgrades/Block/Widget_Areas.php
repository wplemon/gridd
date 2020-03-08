<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Migrate arbitrary content to blocks
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades\Block;

/**
 * The block-migrator object.
 *
 * @since 3.0.0
 */
class Widget_Areas {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'run' ], 1 );
	}

	/**
	 * Runs on "wp".
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function run() {
		// Get an array of all our widget areas.
		$widget_areas = \wp_get_sidebars_widgets();

		// Loop widget areas.
		foreach ( $widget_areas as $id => $widgets ) {

			if ( ! $this->can_migrate_widget_area( $id, $widgets ) ) {
				continue;
			}

			new \Gridd\Upgrades\Block\Widget_Area( [ $id, array_values( $widgets ) ] );
		}
	}

	/**
	 * Check if we can migrate a widget-area to blocks.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param string $id      The widget-area ID.
	 * @param array  $widgets An array of widgets in the widget-area.
	 * @return bool
	 */
	protected function can_migrate_widget_area( $id, $widgets ) {

		if ( 'wp_inactive_widgets' === $id || empty( $widgets ) ) {
			return false;
		}
		foreach ( $widgets as $widget_id ) {
			$can_migrate_widget = $this->can_migrate_widget( $widget_id );
			if ( ! $can_migrate_widget ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Check if we can migrate a widget to block.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @global $wp_registered_widgets
	 * @param string $widget_id The widget-ID.
	 * @return bool
	 */
	protected function can_migrate_widget( $widget_id ) {
		global $wp_registered_widgets;
		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		// Sanity check.
		if (
			! isset( $wp_registered_widgets[ $widget_id ] ) ||
			! isset( $wp_registered_widgets[ $widget_id ]['callback'] ) ||
			! is_array( $wp_registered_widgets[ $widget_id ]['callback'] ) ||
			! isset( $wp_registered_widgets[ $widget_id ]['callback'][0] )
		) {
			return false;
		}

		$class = $wp_registered_widgets[ $widget_id ]['callback'][0];

		if ( ! is_object( $class ) ) {
			return false;
		}

		$widget_blocks = [
			'WP_Widget_Archives'        => 'core/archives',
			'WP_Widget_Media_Audio'     => 'core/audio',
			'WP_Widget_Calendar'        => 'core/calendar',
			'WP_Widget_Categories'      => 'core/categories',
			'WP_Widget_Custom_HTML'     => 'core/html',
			'WP_Widget_Media_Gallery'   => 'core/gallery',
			'WP_Widget_Media_Image'     => 'core/image',
			// 'WP_Widget_Meta' is currently unavailable.
			// 'WP_Widget_Pages' is currently unavailable.
			'WP_Widget_Recent_Comments' => 'core/latest-comments',
			'WP_Widget_RSS'             => 'core/rss',
			// 'WP_Nav_Menu_Widget' is not currently available.
		];

		// Check if this is a widget we can migrate.
		$class_name = get_class( $class );

		return ( isset( $widget_blocks[ $class_name ] ) && isset( $registered_blocks[ $widget_blocks[ $class_name ] ] ) );
	}
}

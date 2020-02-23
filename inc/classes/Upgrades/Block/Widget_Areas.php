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
		global $wp_registered_widgets;
		// Get an array of all our widget areas.
		$widget_areas = \wp_get_sidebars_widgets();

		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		// Loop widget areas.
		foreach ( $widget_areas as $id => $widgets ) {
			if ( 'wp_inactive_widgets' === $id || empty( $widgets ) ) {
				continue;
			}
			$can_migrate        = [];
			$widgets_to_migrate = [];

			foreach ( $widgets as $widget_id ) {

				// Sanity check.
				if (
					! isset( $wp_registered_widgets[ $widget_id ] ) ||
					! isset( $wp_registered_widgets[ $widget_id ]['callback'] ) ||
					! is_array( $wp_registered_widgets[ $widget_id ]['callback'] ) ||
					! isset( $wp_registered_widgets[ $widget_id ]['callback'][0] )
				) {
					$can_migrate[] = false;
					continue;
				}

				$class = $wp_registered_widgets[ $widget_id ]['callback'][0];

				if ( ! is_object( $class ) ) {
					$can_migrate[] = false;
					continue;
				}

				// Check if this is a widget we can migrate.
				$class_name = get_class( $class );
				switch ( $class_name ) {
					case 'WP_Widget_Archives':
						if ( isset( $registered_blocks['core/archives'] ) ) {
							$can_migrate[]        = true;
							$widgets_to_migrate[] = $widget_id;
							break;
						}
						$can_migrate[] = false;
						break;
					case 'WP_Widget_Categories':
						if ( isset( $registered_blocks['core/categories'] ) ) {
							$can_migrate[]        = true;
							$widgets_to_migrate[] = $widget_id;
							break;
						}
						$can_migrate[] = false;
						break;
				}
			}

			// Check if the sidebar can be migrated.
			foreach ( $can_migrate as $check ) {
				if ( ! $check ) {
					$can_migrate = false;
					break;
				}
			}

			// Early exit if the sidebar can't be migrated.
			if ( ! $can_migrate ) {
				continue;
			}

			new \Gridd\Upgrades\Block\Widget_Area( [ $id, $widgets_to_migrate ] );
		}
	}
}

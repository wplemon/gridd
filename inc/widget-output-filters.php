<?php
/**
 * Class Widget_Output_Filters
 *
 * Allows developers to filter the output of any WordPress widget.
 * This class is a verbatim copy from https://github.com/philipnewcomer/widget-output-filters/blob/develop/src/class.Widget_Output_Filters.php
 * with just a couple of tweaks for the Gridd theme.
 *
 * @package Gridd
 * @see https://github.com/philipnewcomer/widget-output-filters
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

/**
 * A singleton class that allows us to add filters to widgets.
 */
class Widget_Output_Filters {

	/**
	 * Contains the single instance of this class.
	 *
	 * @static
	 * @access private
	 * @since 0.1
	 * @var Widget_Output_Filters
	 */
	private static $instance = null;

	/**
	 * Returns the single instance of this class.
	 *
	 * @static
	 * @access public
	 * @since 0.1
	 * @return Widget_Output_Filters The single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initializes the functionality by registering actions and filters.
	 *
	 * @access public
	 * @since 0.1
	 */
	private function __construct() {

		// Priority of 9 to run before the Widget Logic plugin.
		add_filter( 'dynamic_sidebar_params', [ $this, 'filter_dynamic_sidebar_params' ], 9 );
	}

	/**
	 * Replaces the widget's display callback with the Dynamic Sidebar Params display callback, storing the original callback for use later.
	 * The $sidebar_params variable is not modified; it is only used to get the current widget's ID.
	 *
	 * @access public
	 * @since 0.1
	 * @param array $sidebar_params The sidebar parameters.
	 * @return array The sidebar parameters
	 */
	public function filter_dynamic_sidebar_params( $sidebar_params ) {
		if ( is_admin() ) {
			return $sidebar_params;
		}

		global $wp_registered_widgets;
		$current_widget_id = $sidebar_params[0]['widget_id'];

		$wp_registered_widgets[ $current_widget_id ]['original_callback'] = $wp_registered_widgets[ $current_widget_id ]['callback']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
		$wp_registered_widgets[ $current_widget_id ]['callback']          = [ $this, 'display_widget' ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited

		return $sidebar_params;
	}

	/**
	 * Execute the widget's original callback function, filtering its output.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function display_widget() {

		global $wp_registered_widgets;
		$original_callback_params = func_get_args();

		$widget_id         = $original_callback_params[0]['widget_id'];
		$original_callback = $wp_registered_widgets[ $widget_id ]['original_callback'];

		$wp_registered_widgets[ $widget_id ]['callback'] = $original_callback; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited

		$widget_id_base = $original_callback[0]->id_base;
		$sidebar_id     = $original_callback_params[0]['id'];

		if ( is_callable( $original_callback ) ) {

			ob_start();
			call_user_func_array( $original_callback, $original_callback_params );
			$widget_output = ob_get_clean();

			/**
			 * Filter the widget's output.
			 *
			 * @param string $widget_output  The widget's output.
			 * @param string $widget_id_base The widget's base ID.
			 * @param string $widget_id      The widget's full ID.
			 * @param string $sidebar_id     The current sidebar ID.
			 */
			echo apply_filters( 'gridd_widget_output', $widget_output, $widget_id_base, $widget_id, $sidebar_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

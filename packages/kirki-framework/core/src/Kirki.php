<?php
/**
 * The Kirki API class.
 * Takes care of adding panels, sections & fields to the customizer.
 * For documentation please see https://github.com/aristath/kirki/wiki
 *
 * @package     Kirki
 * @category    Core
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Core;

use Kirki\Compatibility\Field;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class acts as an interface.
 * Developers may use this object to add configurations, fields, panels and sections.
 * You can also access all available configurations, fields, panels and sections
 * by accessing the object's static properties.
 */
class Kirki extends Init {

	/**
	 * URL to the Kirki folder.
	 *
	 * @deprecated This is no longer used. Only kept here for backwards compatibility to avoid fatal errors.
	 * @static
	 * @access public
	 * @var string
	 */
	public static $url;

	/**
	 * An array containing all configurations.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $config = [];

	/**
	 * An array containing all fields.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $fields = [];

	/**
	 * An array containing all panels.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $panels = [];

	/**
	 * An array containing all sections.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $sections = [];

	/**
	 * An array containing all panels to be removed.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @var array
	 */
	public static $panels_to_remove = [];

	/**
	 * An array containing all sections to be removed.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @var array
	 */
	public static $sections_to_remove = [];

	/**
	 * An array containing all controls to be removed.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @var array
	 */
	public static $controls_to_remove = [];

	/**
	 * Modules object.
	 *
	 * @access public
	 * @since 3.0.0
	 * @var object
	 */
	public $modules;

	/**
	 * Get the value of an option from the db.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The ID of the configuration corresponding to this field.
	 * @param string $field_id  The field_id (defined as 'settings' in the field arguments).
	 * @return mixed The saved value of the field.
	 */
	public static function get_option( $config_id = '', $field_id = '' ) {
		return Values::get_value( $config_id, $field_id );
	}

	/**
	 * Sets the configuration options.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID.
	 * @param array  $args      The configuration options.
	 */
	public static function add_config( $config_id, $args = [] ) {
		$config                             = Config::get_instance( $config_id, $args );
		$config_args                        = $config->get_config();
		self::$config[ $config_args['id'] ] = $config_args;
	}

	/**
	 * Create a new panel.
	 *
	 * @static
	 * @access public
	 * @param string $id   The ID for this panel.
	 * @param array  $args The panel arguments.
	 */
	public static function add_panel( $id = '', $args = [] ) {
		$args['id'] = $id;
		if ( ! isset( $args['description'] ) ) {
			$args['description'] = '';
		}
		if ( ! isset( $args['priority'] ) ) {
			$args['priority'] = 10;
		}
		if ( ! isset( $args['type'] ) ) {
			$args['type'] = 'default';
		}
		if ( false === strpos( $args['type'], 'kirki-' ) ) {
			$args['type'] = 'kirki-' . $args['type'];
		}
		self::$panels[ $id ] = $args;
	}

	/**
	 * Remove a panel.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @param string $id   The ID for this panel.
	 */
	public static function remove_panel( $id = '' ) {
		if ( ! in_array( $id, self::$panels_to_remove, true ) ) {
			self::$panels_to_remove[] = $id;
		}
	}

	/**
	 * Create a new section.
	 *
	 * @static
	 * @access public
	 * @param string $id   The ID for this section.
	 * @param array  $args The section arguments.
	 */
	public static function add_section( $id, $args ) {
		$args['id'] = $id;
		if ( ! isset( $args['description'] ) ) {
			$args['description'] = '';
		}
		if ( ! isset( $args['priority'] ) ) {
			$args['priority'] = 10;
		}
		if ( ! isset( $args['type'] ) ) {
			$args['type'] = 'default';
		}
		if ( false === strpos( $args['type'], 'kirki-' ) ) {
			$args['type'] = 'kirki-' . $args['type'];
		}

		self::$sections[ $id ] = $args;
	}

	/**
	 * Remove a section.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @param string $id   The ID for this panel.
	 */
	public static function remove_section( $id = '' ) {
		if ( ! in_array( $id, self::$sections_to_remove, true ) ) {
			self::$sections_to_remove[] = $id;
		}
	}

	/**
	 * Create a new field.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID for this field.
	 * @param array  $args      The field arguments.
	 */
	public static function add_field( $config_id, $args = [] ) {
		if ( doing_action( 'customize_register' ) ) {
			_doing_it_wrong( __METHOD__, esc_html__( 'Kirki fields should not be added on customize_register. Please add them directly, or on init.', 'kirki' ), '3.0.10' );
		}

		// Early exit if 'type' is not defined.
		if ( ! isset( $args['type'] ) ) {
			return;
		}

		$str       = str_replace( [ '-', '_' ], ' ', $args['type'] );
		$classname = '\Kirki\Field\\' . str_replace( ' ', '_', ucwords( $str ) );

		$config               = Config::get_instance( $config_id )->get_config();
		$args['kirki_config'] = isset( $args['kirki_config'] ) ? $args['kirki_config'] : $config_id;
		unset( $config['id'] );
		$args = wp_parse_args( $args, $config );

		if ( class_exists( $classname ) ) {
			new $classname( $args );
			return;
		}
		new Field( $args );
	}

	/**
	 * Remove a control.
	 *
	 * @static
	 * @access public
	 * @since 3.0.17
	 * @param string $id The field ID.
	 */
	public static function remove_control( $id ) {
		if ( ! in_array( $id, self::$controls_to_remove, true ) ) {
			self::$controls_to_remove[] = $id;
		}
	}

	/**
	 * Gets a parameter for a config-id.
	 *
	 * @static
	 * @access public
	 * @since 3.0.10
	 * @param string $id    The config-ID.
	 * @param string $param The parameter we want.
	 * @return string
	 */
	public static function get_config_param( $id, $param ) {
		if ( ! isset( self::$config[ $id ] ) || ! isset( self::$config[ $id ][ $param ] ) ) {
			return '';
		}
		return self::$config[ $id ][ $param ];
	}
}

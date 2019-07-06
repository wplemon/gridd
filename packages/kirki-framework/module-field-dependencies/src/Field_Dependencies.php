<?php
/**
 * Automatic field-dependencies scripts calculation for Kirki controls.
 *
 * @package     Kirki
 * @category    Modules
 * @author      Ari Stathopoulos (@aristath)
 * @copyright   Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license    https://opensource.org/licenses/MIT
 * @since       3.0.0
 */

namespace Kirki\Module;

use Kirki\Compatibility\Kirki;
use Kirki\URL;

/**
 * Adds styles to the customizer.
 */
class Field_Dependencies {

	/**
	 * An array of field dependencies.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $dependencies = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'field_dependencies' ] );
		add_filter( 'kirki_field_add_control_args', [ $this, 'field_add_control_args' ] );
	}

	/**
	 * Filter control arguments.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $args The field arguments.
	 * @return array
	 */
	public function field_add_control_args( $args ) {
		if ( isset( $args['active_callback'] ) ) {
			if ( is_array( $args['active_callback'] ) ) {
				if ( ! is_callable( $args['active_callback'] ) ) {
	
					// Bugfix for https://github.com/aristath/kirki/issues/1961.
					foreach ( $args['active_callback'] as $key => $val ) {
						if ( is_callable( $val ) ) {
							unset( $args['active_callback'][ $key ] );
						}
					}
					if ( isset( $args['active_callback'][0] ) ) {
						$args['required'] = $args['active_callback'];
					}
				}
			}
	
			if ( ! empty( $args['required'] ) ) {
				$this->dependencies[ $args['settings'] ] = $args['required'];
				$args['active_callback'] = '__return_true';
				return $args;
			}
			// No need to proceed any further if we're using the default value.
			if ( '__return_true' === $args['active_callback'] ) {
				return $args;
			}

			// Make sure the function is callable, otherwise fallback to __return_true.
			if ( ! is_callable( $args['active_callback'] ) ) {
				$args['active_callback'] = '__return_true';
			}
		}
		return $args;
	}

	/**
	 * Enqueues the field-dependencies script
	 * and adds variables to it using the wp_localize_script function.
	 * The rest is handled via JS.
	 *
	 * @access public
	 * @return void
	 */
	public function field_dependencies() {
		wp_enqueue_script( 'kirki_field_dependencies', URL::get_from_path( __DIR__ . '/assets/scripts/script.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], '4.0', true );
		wp_localize_script( 'kirki_field_dependencies', 'kirkiControlDependencies', $this->dependencies );
	}
}

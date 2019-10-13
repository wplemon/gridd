<?php
/**
 * Customizer Control: kirki-wcag-link-color.
 *
 * @package   kirki-wcag-link-color
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license   GPL2.0+
 * @since     1.0
 */

namespace WPLemon\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * React-color control.
 *
 * @since 1.0
 */
class WCAGLinkColor extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-wcag-link-color';

	/**
	 * The control version.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '0.1';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue() {
		parent::enqueue();

		// Enqueue the script.
		wp_enqueue_script( 'wplemon-control-auto-links-colorpicker', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/main.js' ), [ 'customize-controls', 'wp-element', 'jquery', 'customize-base', 'kirki-dynamic-control', 'wp-color-picker' ], self::$control_ver, false );

		// Enqueue the style.
		wp_enqueue_style( 'wplemon-control-auto-links-colorpicker-style', URL::get_from_path( dirname( __DIR__ ) . '/style.css' ), [], self::$control_ver );
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function content_template() {}
}

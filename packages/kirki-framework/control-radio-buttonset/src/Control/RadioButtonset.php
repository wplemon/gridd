<?php
/**
 * Customizer Control: kirki-wcag-lc.
 *
 * @package   kirki-wcag-lc
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license   GPL2.0+
 * @since     2.0
 */

namespace Kirki\Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * React-color control.
 *
 * @since 2.0
 */
class RadioButtonset extends \WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $type = 'kirki-react-radio-buttonset';

	/**
	 * Whether we want to hide the input or not.
	 *
	 * @access public
	 * @since 2.0
	 * @var bool
	 */
	public $hide_input = false;

	/**
	 * The control version.
	 *
	 * @static
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public static $control_ver = '2.0';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 2.0
	 * @return void
	 */
	public function enqueue() {
		parent::enqueue();

		if ( class_exists( '\Kirki\URL' ) ) {
			$folder_url = \Kirki\URL::get_from_path( dirname( dirname( __DIR__ ) ) );
		} else {
			$folder_url = \str_replace(
				\wp_normalize_path( \untrailingslashit( WP_CONTENT_DIR ) ),
				\untrailingslashit( \content_url() ),
				dirname( dirname( __DIR__ ) )
			);
		}

		// Enqueue the script.
		wp_enqueue_script(
			'kirki-react-radio-buttonset',
			$folder_url . '/dist/main.js',
			[ 'customize-controls', 'wp-element', 'jquery', 'customize-base' ],
			self::$control_ver,
			false
		);

		// Enqueue the style.
		wp_enqueue_style(
			'kirki-react-radio-buttonset',
			$folder_url . '/src/style.css',
			[],
			self::$control_ver
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @since 2.0
	 * @see WP_Customize_Control::to_json()
	 * @return void
	 */
	public function to_json() {

		// Get the basics from the parent class.
		parent::to_json();

		$this->json['hideInput'] = $this->hide_input;

		$this->json['choices'] = $this->choices;
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
	 * @since 2.0
	 * @return void
	 */
	protected function content_template() {}
}

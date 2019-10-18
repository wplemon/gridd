<?php
/**
 * Customizer Control: kirki-wcag-tc.
 *
 * @package   kirki-wcag-ltcc
 * @copyright Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license   GPL2.0+
 * @since     2.0
 */

namespace WPLemon\Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * React-color control.
 *
 * @since 2.0
 */
class WCAGTextColor extends \WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $type = 'kirki-wcag-tc';

	/**
	 * The control version.
	 *
	 * @static
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public static $control_ver = '2.0';

	// Start compatibility with Kirki v3.0 API.
	/**
	 * Used to automatically generate all CSS output.
	 *
	 * Whitelisting property for use in Kirki modules.
	 *
	 * @access public
	 * @since 2.0
	 * @var array
	 */
	public $output = [];

	/**
	 * Data type
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Option name (if using options).
	 *
	 * Whitelisting property for use in Kirki modules.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $option_name = false;

	/**
	 * Whitelisting the "css_vars" argument for use in Kirki modules.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $css_vars = '';

	/**
	 * Parent setting.
	 *
	 * Used for composite controls to denote the setting that should be saved.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $parent_setting;

	/**
	 * Wrapper attributes.
	 *
	 * Used for composite controls.
	 *
	 * @access public
	 * @since 2.0
	 * @var string
	 */
	public $wrapper_atts;
	// End compatibility with Kirki v3.0 API.

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
			'wplemon-control-auto-text-colorpicker',
			$folder_url . '/dist/main.js',
			[ 'customize-controls', 'wp-element', 'jquery', 'customize-base', 'wp-color-picker' ],
			time(),
			false
		);

		// Enqueue the style.
		wp_enqueue_style(
			'wplemon-control-auto-text-colorpicker-style',
			$folder_url . '/src/style.css',
			[],
			time()
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

		$strings = ( isset( $this->choices['18n'] ) ) ? $this->choices['18n'] : [];

		$this->json['i18n'] = wp_parse_args( $strings,[
			'auto'        => esc_html__( 'Auto', 'kirki-pro' ),
			'recommended' => esc_html__( 'Recommended', 'kirki-pro' ),
			'custom'      => esc_html__( 'Custom', 'kirki-pro' ),
			'a11yRating'  => esc_html__( 'WCAG Rating', 'kirki-pro' ),
			'contrastBg'  => esc_html__( 'Contrast with background', 'kirki-pro' ),
			'contrastSt'  => esc_html__( 'Contrast with surrounding text', 'kirki-pro' ),
		] );

		// Start compatibility with Kirki v3.0 API.
		$this->json['default'] = ( isset( $this->default ) ) ? $this->default : $this->setting->default;
		$this->json['output'] = $this->output;
		$this->json['value'] = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link'] = $this->get_link();
		$this->json['id'] = $this->id;
		$this->json['kirkiOptionType'] = $this->option_type;
		$this->json['kirkiOptionName'] = $this->option_name;
		$this->json['css-var'] = $this->css_vars;
		$this->json['parent_setting'] = $this->parent_setting;
		$this->json['wrapper_atts'] = $this->wrapper_atts;
		// End compatibility with Kirki 3.0 API.
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

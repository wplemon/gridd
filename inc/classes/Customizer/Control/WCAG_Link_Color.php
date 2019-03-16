<?php
/**
 * The Control class.
 * This is a stripped-down version of the Kirki-WCAG-Link-Color control.
 * For more details see https://wplemon.com/downloads/kirki-wcag-link-colorpicker/
 *
 * @package Gridd
 * @since 1.0
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Customizer\Control;

/**
 * The main control class.
 *
 * @since 1.0
 */
class WCAG_Link_Color extends \WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'gridd-wcag-lc';

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @access public
	 * @var array
	 */
	public $output = [];

	/**
	 * Data type
	 *
	 * @access public
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Option name (if using options).
	 *
	 * @access public
	 * @var string
	 */
	public $option_name = false;

	/**
	 * The kirki_config we're using for this control
	 *
	 * @access public
	 * @var string
	 */
	public $kirki_config = 'global';

	/**
	 * Whitelisting the "required" argument.
	 *
	 * @since 3.0.17
	 * @access public
	 * @var array
	 */
	public $required = [];

	/**
	 * Whitelisting the "preset" argument.
	 *
	 * @since 3.0.26
	 * @access public
	 * @var array
	 */
	public $preset = [];

	/**
	 * Whitelisting the "css_vars" argument.
	 *
	 * @since 3.0.28
	 * @access public
	 * @var string
	 */
	public $css_vars = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'wcag_colors', get_template_directory_uri() . '/assets/js/wcagColors.min.js', [], '1.0', false );
		wp_enqueue_script( 'kirki_gridd_wcag_link_color', get_template_directory_uri() . '/assets/js/customizer-kirki-gridd-linkcolor.min.js', [], '1.0', false );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		// Get the basics from the parent class.
		parent::to_json();

		// Default value.
		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		// Required.
		$this->json['required'] = $this->required;

		// Output.
		$this->json['output'] = $this->output;

		// Value.
		$this->json['value'] = $this->value();

		// Choices.
		$this->json['choices'] = $this->choices;

		// The link.
		$this->json['link'] = $this->get_link();

		// The ID.
		$this->json['id'] = $this->id;

		// The kirki-config.
		$this->json['kirkiConfig'] = $this->kirki_config;

		// The option-type.
		$this->json['kirkiOptionType'] = $this->option_type;

		// The option-name.
		$this->json['kirkiOptionName'] = $this->option_name;

		// The CSS-Variables.
		$this->json['css-var'] = $this->css_vars;
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
	 */
	protected function content_template() {
		?>
		<# if ( data.label ) { #>
			<label><span class="customize-control-title">{{{ data.label }}}</span></label>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="kirki-input-container auto">

			<!-- Color input. The actual input gets added via JS from the script. -->
			<div class="color-hue-container"></div>
		</div>
		<!-- Hidden input. -->
		<input class="hidden-value-hex" type="hidden" value="{{ data.value }}" {{{ data.link }}} />
		<?php
	}

	/**
	 * Adding an empty function here prevents PHP errors from to_json() in the parent class.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function render_content() {}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

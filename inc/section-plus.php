<?php
/**
 * Plus Customizer Section.
 *
 * @package Gridd
 * @since 1.0
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

/**
 * A pseudo-section with the go-plus link.
 *
 * @since 1.0
 */
class Section_Plus extends \WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0
	 * @access public
	 * @var    string
	 */
	public $type = 'gridd_plus';

	/**
	 * Custom button text to output.
	 *
	 * @since  1.0
	 * @access public
	 * @var    string
	 */
	public $button_label = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $button_url = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0
	 * @access public
	 * @return array
	 */
	public function json() {
		$json = parent::json();

		$json['button_label'] = $this->button_label;
		$json['button_url']   = esc_url( $this->button_url );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0
	 * @access public
	 * @return void
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<h3 class="accordion-section-title">
				{{ data.title }}
				<a href="{{ data.button_url }}" class="button alignright" target="_blank">{{ data.button_label }}</a>
			</h3>
		</li>
		<?php
	}
}

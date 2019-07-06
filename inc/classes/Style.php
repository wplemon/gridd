<?php
/**
 * Stylesheets generator.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Theme;

/**
 * Template handler.
 *
 * @since 1.0
 */
class Style {

	/**
	 * An array of instances.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private static $instances = [];

	/**
	 * The context of this instance.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $context = '';

	/**
	 * CSS vars to replace.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $vars = [];

	/**
	 * CSS as a string.
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $css = '';

	/**
	 * Get an instance or create one if it doesn't already exist.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $context The context of this instance.
	 * @return Style
	 */
	public static function get_instance( $context ) {
		if ( ! isset( self::$instances[ $context ] ) ) {
			self::$instances[ $context ] = new self( $context );
		}
		return self::$instances[ $context ];
	}

	/**
	 * Constructor.
	 * Sets the $context for this instance so that we may use it in our filters.
	 *
	 * @access private
	 * @param string $context The context of this instance.
	 * @since 1.0
	 */
	private function __construct( $context = '' ) {
		$this->context = $context;
		do_action( 'gridd_style', $this );
	}

	/**
	 * Add vars.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $vars An array of css-vars to replace.
	 * @return void
	 */
	public function add_vars( $vars ) {
		$this->vars = apply_filters( 'gridd_style_vars', array_merge( $this->vars, $vars ), $this->context );
	}

	/**
	 * Add CSS from string.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $css The CSS to add.
	 * @return Style
	 */
	public function add_string( $css ) {
		$this->css .= (string) $css;
		return $this;
	}

	/**
	 * Add CSS from file path.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $path Absolute path to a file.
	 * @return Style
	 */
	public function add_file( $path ) {
		if ( file_exists( $path ) ) {
			$this->css .= Theme::get_fcontents( $path, true );
		}
		return $this;
	}

	/**
	 * Replace strings in the CSS.
	 *
	 * @access public
	 * @since 1.0
	 * @param string|array $search  The 1st argument in str_replace.
	 * @param string|array $replace The 2nd argument in str_replace.
	 * @return void
	 */
	public function replace( $search, $replace ) {
		/**
		 * First we replace "(" and ")" with "\(" and "\)" respectively,
		 * then we use preg_replace instead of str_replace
		 * because str_replace messes-up the CSS, removed semicolons etc.
		 */
		$search    = str_replace( [ '(', ')' ], [ '\\(', '\\)' ], $search );
		$this->css = preg_replace( (string) "/$search/", (string) $replace, (string) $this->css );
	}

	/**
	 * Gets the CSS, replacing all vars.
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 */
	public function get_css() {

		// Don't replace css-vars if we're on the customizer.
		if ( is_customize_preview() ) {
			return $this->css;
		}

		/**
		 * WIP: Replace css-vars.
		foreach ( $this->vars as $name => $value ) {
			$this->replace( "var($name)", $value );
		}
		*/
		return apply_filters( 'gridd_style_css', $this->css, $this->context );
	}

	/**
	 * Print the CSS.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $id The <style> ID.
	 * @return void
	 */
	public function the_css( $id ) {
		echo ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? "\r\n<style id=\"" . esc_attr( $id ) . "\">\r\n" : '<style id="' . esc_attr( $id ) . '">';
		/**
		 * This is CSS, do not escape. Nothing here is unescaped user input, everything has already been sanitized properly.
		 */
		echo $this->get_css(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? "\r\n</style>\r\n" : '</style>';
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

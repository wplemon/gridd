<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Stylesheets generator.
 *
 * @package Gridd
 */

namespace Gridd;

/**
 * CSS handler.
 *
 * @since 1.0
 */
class CSS {

	/**
	 * Arguments array.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var array
	 */
	protected $args;

	/**
	 * An array of ernqueued files.
	 *
	 * Prevents duplicate styles.
	 *
	 * @static
	 * @access protected
	 * @since 3.0.0
	 * @var array
	 */
	protected static $enqueued_files = [];

	/**
	 * The styles.
	 *
	 * @static
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected static $css = '';

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param array $args The arguments array.
	 * @since 1.0
	 */
	public function __construct( $args = [] ) {
		$this->args = wp_parse_args(
			$args,
			[
				'id'       => 'gridd-global-styles',
				'hook'     => 'wp_footer',
				'priority' => 10,
			]
		);
		$this->init();
	}

	/**
	 * Run hooks.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return void
	 */
	protected function init() {
		add_action( $this->args['hook'], [ $this, 'print_styles' ], $this->args['priority'] );
	}

	/**
	 * Print styles.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function print_styles() {
		if ( ! self::$css ) {
			return;
		}
		$css = apply_filters( 'gridd_css', self::$css );

		if ( is_child_theme() && apply_filters( 'gridd_load_child_theme_styles', true ) ) {
			// Note to code reviewers: wp_strip_all_tags here is sufficient escape to ensure everything is interpreted as CSS.
			$css .= file_get_contents( get_stylesheet_directory() . '/style.css', true );
		}

		// Note to code reviewers: wp_strip_all_tags here is sufficient escape to ensure everything is interpreted as CSS.
		echo '<style id="' . esc_attr( $this->args['id'] ) . '">' . wp_strip_all_tags( $css ) . '</style>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add file.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @param string $path  Absolute file path.
	 * @param string $media The media query.
	 * @return void
	 */
	public static function add_file( $path, $media = '' ) {

		if ( isset( self::$enqueued_files[ $path ] ) && $media === self::$enqueued_files[ $path ] ) {
			return;
		}

		if ( $media ) {
			self::$css .= "@media $media{";
		}
		self::$css .= file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		if ( $media ) {
			self::$css .= '}';
		}

		self::$enqueued_files[ $path ] = $media;
	}

	/**
	 * Add string.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @param string $css   The css to add.
	 * @param string $media The media query.
	 * @return void
	 */
	public static function add_string( $css, $media = '' ) {
		if ( $media ) {
			self::$css .= "@media $media{";
		}
		self::$css .= $css;
		if ( $media ) {
			self::$css .= '}';
		}
	}

	/**
	 * Add child-theme styles.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function child_theme_styles() {
		if ( is_child_theme() && apply_filters( 'gridd_load_child_theme_styles', true ) && 'grid-part/content' === $this->context ) {
			// Note to code reviewers: wp_strip_all_tags here is sufficient escape to ensure everything is interpreted as CSS.
			echo '<style>' . wp_strip_all_tags( Theme::get_fcontents( get_stylesheet_directory() . '/style.css', true ), true ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

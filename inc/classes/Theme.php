<?php
/**
 * The main theme class.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\Customizer;
use Gridd\Template;
use Gridd\Grid;
use Gridd\Widget_Areas;
use Gridd\Blog;
use Gridd\Scripts;
use Gridd\Jetpack;
use Gridd\WooCommerce;
use Gridd\AMP;

/**
 * The main theme class.
 *
 * @since 1.0
 */
class Theme {

	/**
	 * The theme version.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * The Gridd\Customizer object.
	 *
	 * @access public
	 * @since 1.0
	 * @var Gridd\Customizer
	 */
	public $customizer;

	/**
	 * The Gridd\Blog object.
	 *
	 * @access public
	 * @since 1.0
	 * @var Gridd\Blog
	 */
	public $blog;

	/**
	 * The Gridd\Scripts object.
	 *
	 * @access public
	 * @since 1.0
	 * @var Gridd\Scripts
	 */
	public $scripts;

	/**
	 * The Gridd\Jetpack object.
	 *
	 * @access public
	 * @since 1.0
	 * @var Gridd\Jetpack
	 */
	public $jetpack;

	/**
	 * The Gridd\WooCommerce object.
	 *
	 * @access public
	 * @since 1.0
	 * @var Gridd\WooCommerce
	 */
	public $wc;

	/**
	 * A single instance of this object.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var Gridd
	 */
	private static $instance;

	/**
	 * Get an instance of this object.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return Gridd
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access private
	 */
	private function __construct() {

		// Init Gridd\Blog.
		$this->blog = new Blog();

		// Init Gridd\Customizer.
		$this->customizer = new Customizer();

		// Init Gridd\Scripts.
		$this->scripts = new Scripts();

		// Init Gridd\Jetpack.
		$this->jetpack = new Jetpack();

		// Loads the WooCommerce class.
		$this->wc = new WooCommerce();

		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function body_class( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}
		$classes[] = 'gridd-header-layout-' . get_theme_mod( 'gridd_header_layout', 'top' );
		if ( ! AMP::is_active() ) {
			$classes[] = 'gridd-amp';
		}
		if ( is_archive() || is_home() ) {
			$classes[] = 'gridd-post-type-archive-' . get_post_type();
		}

		return $classes;
	}

	/**
	 * Adds theme-supports.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			[
				'menu-handheld' => esc_html__( 'Mobile Navigation', 'gridd' ),
			]
		);

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			[
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			]
		);

		// Add custom-headers support.
		add_theme_support(
			'custom-header',
			[
				'default-image'      => '',
				'default-text-color' => '#000000',
				'width'              => 0,
				'height'             => 0,
				'flex-height'        => true,
				'flex-width'         => true,
				'uploads'            => true,
				'random-default'     => false,
				'wp-head-callback'   => '',
			]
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'gridd_custom_background_args',
				[
					'default-color' => 'ffffff',
					'default-image' => '',
				]
			)
		);

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);

		/**
		 * Gutenberg.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		if ( 50 > \ariColor::newColor( get_theme_mod( 'gridd_grid_content_background_color', '#ffffff' ) )->lightness ) {
			add_theme_support( 'dark-editor-style' );
		}
		add_theme_support( 'responsive-embeds' );
		add_editor_style( 'assets/css/admin/editor.min.css' );

		add_theme_support(
			'editor-color-palette',
			[
				[
					'name'  => esc_attr__( 'Pale Pink', 'gridd' ),
					'slug'  => 'pale-pink',
					'color' => '#f78da7',
				],
				[
					'name'  => esc_attr__( 'Vivid Red', 'gridd' ),
					'slug'  => 'vivid-red',
					'color' => '#cf2e2e',
				],
				[
					'name'  => esc_attr__( 'Luminous Vivid Orange', 'gridd' ),
					'slug'  => 'luminous-vivid-orange',
					'color' => '#ff6900',
				],
				[
					'name'  => esc_attr__( 'Luminous Vivid Amber', 'gridd' ),
					'slug'  => 'luminous-vivid-amber',
					'color' => '#fcb900',
				],
				[
					'name'  => esc_attr__( 'Light Green Cyan', 'gridd' ),
					'slug'  => 'light-green-cyan',
					'color' => '#7bdcb5',
				],
				[
					'name'  => esc_attr__( 'Vivid Green Cyan', 'gridd' ),
					'slug'  => 'vivid-green-cyan',
					'color' => '#00d084',
				],
				[
					'name'  => esc_attr__( 'Pale Cyan Blue', 'gridd' ),
					'slug'  => 'pale-cyan-blue',
					'color' => '#8ed1fc',
				],
				[
					'name'  => esc_attr__( 'cyan', 'gridd' ),
					'slug'  => 'theme-cyan',
					'color' => '#26C6DA',
				],
				[
					'name'  => esc_html__( 'Vivid Cyan Blue', 'gridd' ),
					'slug'  => 'vivid-cyan-blue',
					'color' => '#0693e3',
				],
				[
					'name'  => esc_attr__( 'White', 'gridd' ),
					'slug'  => 'theme-white',
					'color' => '#fff',
				],
				[
					'name'  => esc_attr__( 'Very Light Gray', 'gridd' ),
					'slug'  => 'very-light-gray',
					'color' => '#eee',
				],
				[
					'name'  => esc_html__( 'Cyan Bluish Gray', 'gridd' ),
					'slug'  => 'cyan-bluish-gray',
					'color' => '#abb8c3',
				],
				[
					'name'  => esc_attr__( 'Blue Gray', 'gridd' ),
					'slug'  => 'blue-gray',
					'color' => '#546E7A',
				],
				[
					'name'  => esc_attr__( 'Very Dark Gray', 'gridd' ),
					'slug'  => 'very-dark-gray',
					'color' => '#313131',
				],
				[
					'name'  => esc_attr__( 'Black', 'gridd' ),
					'slug'  => 'theme-black',
					'color' => '#000',
				],
			]
		);

		// Starter Content.
		add_theme_support(
			'starter-content',
			[

				// Default widgets.
				'widgets' => [
					'sidebar-1' => [ 'search', 'recent-posts', 'recent-comments' ],
					'sidebar-2' => [ 'meta' ],
					'sidebar-3' => [ 'search', 'recent-posts', 'recent-comments', 'meta' ],
					'sidebar-4' => [ 'search', 'recent-posts', 'recent-comments', 'meta' ],
				],
			]
		);
	}

	/**
	 * Define the content-width.
	 *
	 * @access public
	 * @since 1.0
	 * @global int $content_width
	 * @return void
	 */
	public function content_width() {
		$gridd_content_width = 960;

		// Get the width from theme_mod.
		$max_width = get_theme_mod( 'gridd_grid_content_max_width', '45em' );

		if ( false === strpos( $max_width, 'calc' ) ) {
			
			// If width is defined in pixels then this is easy.
			if ( false !== strpos( $max_width, 'px' ) ) {
				$gridd_content_width = intval( $max_width );
			}

			// If width is in em or rem units we need to do some calculations.
			if ( false !== strpos( $max_width, 'em' ) ) {
				$base_font_size      = get_theme_mod( 'gridd_body_font_size', 18 );
				$ratio               = get_theme_mod( 'gridd_fluid_typography_ratio' );
				$font_size           = $ratio * 1440 / 100 + intval( $base_font_size ); // 1440 is an average width for screen size.
				$gridd_content_width = $font_size * intval( $max_width );
			}
		}

		$GLOBALS['content_width'] = apply_filters( 'gridd_content_width', $gridd_content_width );
	}

	/**
	 * Determine if we're using the PRO plugin or not.
	 *
	 * @static
	 * @since 1.0
	 * @return bool
	 */
	public static function is_plus_active() {
		return ( defined( 'GRIDD_PLUS_PATH' ) );
	}

	/**
	 * Hookable get_template_part() function.
	 * Allows us to get templates from a plugin or any other path using custom hooks.
	 *
	 * @static
	 * @access public
	 * @since 1.0.3
	 * @param string $slug The template slug.
	 * @param string $name The template name.
	 * @see https://developer.wordpress.org/reference/functions/get_template_part/
	 * @return void
	 */
	public static function get_template_part( $slug, $name = null ) {
		$custom_path = false;
		/**
		 * Determine if we want to use a custom path for this template-part.
		 *
		 * @since 1.0.3
		 * @param string|false $custom_path The custom template-part path. Defaults to false. Use absolute path.
		 * @param string       $slug        The template slug.
		 * @param string       $name        The template name.
		 * @return string|false
		 */
		$custom_path = apply_filters( 'gridd_get_template_part', $custom_path, $slug, $name );
		if ( $custom_path ) {
			if ( file_exists( $custom_path ) ) {
				include $custom_path;
			}
			return;
		}
		// Get the default template part.
		get_template_part( $slug, $name );
	}

	/**
	 * Generates the HTML for a toggle button.
	 *
	 * @static
	 * @access public
	 * @param array $args The button arguments.
	 * @since 1.0
	 */
	public static function get_toggle_button( $args ) {

		$html = '';

		if ( AMP::is_active() ) {

			// Create new state for managing storing the whether the sub-menu is expanded.
			$html .= '<amp-state id="' . esc_attr( $args['expanded_state_id'] ) . '">';
			$html .= '<script type="application/json">' . $args['expanded'] . '</script>';
			$html .= '</amp-state>';
		}

		if ( ! isset( $args['classes'] ) ) {
			$args['classes'] = [];
		}
		$args['classes'][] = 'gridd-toggle';
		$classes           = implode( ' ', array_unique( $args['classes'] ) );

		$button_atts = [
			'aria-expanded' => 'false',
		];

		if ( AMP::is_active() ) {
			$button_atts['[class]']         = '(' . $args['expanded_state_id'] . '?\'' . $classes . ' toggled-on\':\'' . $classes . '\')';
			$button_atts['[aria-expanded]'] = "{$args['expanded_state_id']} ? 'true' : 'false'";
			$button_atts['on']              = "tap:AMP.setState({ {$args['expanded_state_id']}: ! {$args['expanded_state_id']} })";
		}

		/*
		* Create the toggle button which mutates the state and which has class and
		* aria-expanded attributes which react to the state changes.
		*/
		$html .= '<button class="' . $classes . '"';
		foreach ( $button_atts as $key => $val ) {
			if ( ! empty( $key ) ) {
				$html .= ' ' . $key . '="' . $val . '"';
			}
		}
		$html .= '>';

		if ( AMP::is_active() && isset( $args['screen_reader_label_collapse'] ) && isset( $args['screen_reader_label_expand'] ) ) {

			// Let the screen reader text in the button also update based on the expanded state.
			$html .= '<span class="screen-reader-text"';
			$html .= ' [text]="' . $args['expanded_state_id'] . '?\'' . esc_attr( $args['screen_reader_label_collapse'] ) . '\':\'' . esc_attr( $args['screen_reader_label_expand'] ) . '\'">';
			$html .= esc_html( $args['screen_reader_label_expand'] );
		} elseif ( isset( $args['screen_reader_label_toggle'] ) ) {
			$html .= '<span class="screen-reader-text">' . $args['screen_reader_label_toggle'] . '</span>';
		}
		$html .= '</span>';
		$html .= $args['label'];
		$html .= '</button>';

		return apply_filters( 'gridd_get_toggle_button', $html );
	}

	/**
	 * Utility function to get the contents of a non-executable file as plain text.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @param string $path     The file path.
	 * @param bool   $absolute Set to true if we have an absolute path instead of relative to the theme root.
	 * @return string          The file contents or empty string if no file was found.
	 */
	public static function get_fcontents( $path, $absolute = false ) {
		ob_start();
		if ( $absolute && file_exists( $path ) ) {
			include $path;
		} else {
			include locate_template( $path, false, false );
		}
		return ob_get_clean();
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The main theme class.
 *
 * @package Gridd
 */

namespace Gridd;

use Gridd\Customizer;
use Gridd\Blog;
use Gridd\Scripts;
use Gridd\Jetpack;
use Gridd\WooCommerce;

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

		new \Gridd\Block_Styles();

		add_filter( 'body_class', [ $this, 'body_class' ] );
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 1, 1 );
		add_action( 'wp_head', [ $this, 'add_color_palette_styles' ] );
		add_filter( 'kirki_path_url', [ $this, 'filter_kirki_url_path' ], 1, 2 );
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
		if ( is_archive() || is_home() ) {
			$classes[] = 'gridd-post-type-archive-' . get_post_type();
		}

		$classes[] = get_theme_mod( 'custom_body_typography' ) ? 'gridd-has-custom-body-typography' : 'gridd-has-system-body-typography';
		$classes[] = get_theme_mod( 'custom_headers_typography' ) ? 'gridd-has-custom-headers-typography' : 'gridd-has-system-headers-typography';

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
		if ( ! get_theme_mod( 'disable_editor_styles' ) ) {
			add_theme_support( 'editor-styles' );
			if ( 50 > \ariColor::newColor( get_theme_mod( 'content_background_color', '#ffffff' ) )->lightness ) {
				add_theme_support( 'dark-editor-style' );
			}
		}
		add_theme_support( 'responsive-embeds' );
		add_editor_style( 'assets/css/admin/editor.min.css' );
		add_theme_support( 'editor-color-palette', self::get_color_palette() );

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
		$max_width = get_theme_mod( 'content_max_width', '45em' );

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
		if ( null === $name ) {
			if ( is_archive() ) {
				$name = 'archive';
			} elseif ( is_singular() ) {
				$name = 'singular';
			}
		}

		/**
		 * Determine if we want to use a custom path for this template-part.
		 *
		 * @since 1.0.3
		 * @param string|false $custom_path The custom template-part path. Defaults to false. Use absolute path.
		 * @param string       $slug        The template slug.
		 * @param string       $name        The template name.
		 * @return string|false
		 */
		$custom_path = apply_filters( 'gridd_get_template_part', false, $slug, $name );
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

		if ( ! isset( $args['classes'] ) ) {
			$args['classes'] = [];
		}
		$args['classes'][] = 'gridd-toggle';
		$classes           = implode( ' ', array_unique( $args['classes'] ) );

		$button_atts = [
			'aria-expanded' => 'false',
		];

		$uid                     = wp_rand( 0, 99 ) . substr( str_shuffle( md5( microtime() ) ), 0, 10 );
		$button_atts['data-uid'] = $uid;
		$button_atts['onclick']  = 'griddToggleButtonClick(\'' . $uid . '\')';

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

		if ( isset( $args['screen_reader_label_toggle'] ) ) {
			$html .= '<span class="screen-reader-text">' . $args['screen_reader_label_toggle'] . '</span>';
		}

		$html .= '</span>';
		$html .= $args['label'];
		$html .= '</button>';

		return apply_filters( 'gridd_get_toggle_button', $html, $args );
	}

	/**
	 * Utility method to get the contents of a non-executable file as plain text.
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

	/**
	 * Utility method.
	 * Prints an element's attributes.
	 *
	 * @static
	 * @access public
	 * @since 1.0.8
	 * @param array  $atts           The element attributes we want to print.
	 * @param string $filter_context Argument passed-on as the 2nd param in the gridd_print_attributes filter.
	 * @return void
	 */
	public static function print_attributes( $atts, $filter_context = false ) {
		$atts = apply_filters( 'gridd_print_attributes', $atts, $filter_context );
		$i    = 0;
		foreach ( $atts as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ' ', $value );
			}
			// Add a space in the beginning if this is not our first rodeo.
			echo ( $i ) ? ' ' : '';
			echo esc_html( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	/**
	 * Enqueue extra assets for the editor.
	 *
	 * @access public
	 * @since 1.0.19
	 * @return void
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script( 'gridd-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), [ 'wp-blocks', 'wp-dom' ], GRIDD_VERSION, true );
	}

	/**
	 * Get a palette of colors.
	 *
	 * @static
	 * @access public
	 * @since 2.0.0
	 * @return array
	 */
	public static function get_colorpicker_palette() {
		$palette = self::get_color_palette();
		$colors  = [];
		foreach ( $palette as $item ) {
			$colors[] = $item['color'];
		}
		return $colors;
	}

	/**
	 * Gets the color-palette.
	 *
	 * @static
	 * @access public
	 * @since 2.0.0
	 * @param bool $return_defaults Whether we just want to get the defaults or not.
	 * @return array
	 */
	public static function get_color_palette( $return_defaults = false ) {
		// phpcs:disable Squiz.PHP.CommentedOutCode
		$defaults = [
			[
				'name'  => '',
				'slug'  => 'custom-color-1',
				'color' => '#ffffff',
			],
			[
				'name'  => '',
				'slug'  => 'custom-color-2',
				'color' => '#f5f7f9',
			],
			// [
			// 'name'  => '',
			// 'slug'  => 'custom-color-3',
			// 'color' => '#f4f4e1'
			// ],
			[
				'name'  => '',
				'slug'  => 'custom-color-4',
				'color' => '#f0f3f6',
			],
			[
				'name'  => esc_html__( 'Very Light Gray', 'gridd' ),
				'slug'  => 'very-light-gray',
				'color' => '#eeeeee',
			],
			// [
			// 'name'  => esc_html__( 'Cyan Bluish Gray', 'gridd' ),
			// 'slug'  => 'cyan-bluish-gray',
			// 'color' => '#abb8c3',
			// ],
			[
				'name'  => esc_html__( 'Very Dark Gray', 'gridd' ),
				'slug'  => 'very-dark-gray',
				'color' => '#313131',
			],
			// [
			// 'name'  => '',
			// 'slug'  => 'custom-color-5',
			// 'color' => '#1a1a1d'
			// ],
			[
				'name'  => '',
				'slug'  => 'custom-color-6',
				'color' => '#000000',
			],
			[
				'name'  => esc_html__( 'Pale Pink', 'gridd' ),
				'slug'  => 'pale-pink',
				'color' => '#f78da7',
			],
			[
				'name'  => esc_html__( 'Vivid Red', 'gridd' ),
				'slug'  => 'vivid-red',
				'color' => '#cf2e2e',
			],
			[
				'name'  => esc_html__( 'Luminous Vivid Orange', 'gridd' ),
				'slug'  => 'luminous-vivid-orange',
				'color' => '#ff6900',
			],
			[
				'name'  => esc_html__( 'Luminous Vivid Amber', 'gridd' ),
				'slug'  => 'luminous-vivid-amber',
				'color' => '#fcb900',
			],
			[
				'name'  => esc_html__( 'Light Green Cyan', 'gridd' ),
				'slug'  => 'light-green-cyan',
				'color' => '#7bdcb5',
			],
			[
				'name'  => esc_html__( 'Vivid Green Cyan', 'gridd' ),
				'slug'  => 'vivid-green-cyan',
				'color' => '#00d084',
			],
			[
				'name'  => esc_html__( 'Pale Cyan Blue', 'gridd' ),
				'slug'  => 'pale-cyan-blue',
				'color' => '#8ed1fc',
			],
			[
				'name'  => esc_html__( 'Vivid Cyan Blue', 'gridd' ),
				'slug'  => 'vivid-cyan-blue',
				'color' => '#0693e3',
			],
		];

		// phpcs:enable Squiz.PHP.CommentedOutCode

		if ( $return_defaults ) {
			return $defaults;
		}

		$palette = get_theme_mod( 'custom_color_palette', $defaults );
		if ( \is_string( $palette ) ) {
			$palette = json_decode( $palette );
		}
		return $palette;
	}

	/**
	 * Prints styles for our color-palettes.
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function add_color_palette_styles() {
		$palette = self::get_color_palette();
		$styles  = '';

		// Add the css-variables.
		$styles .= ':root{';
		foreach ( $palette as $item ) {
			$styles .= '--' . $item['slug'] . ':' . esc_html( $item['color'] ) . ';';
		}
		$styles .= '}';

		// Add color & background-color styles.
		foreach ( $palette as $item ) {
			$styles .= '.has-' . $item['slug'] . '-color.has-' . $item['slug'] . '-color{--element-color:var(--' . $item['slug'] . ');}';
			$styles .= '.has-' . $item['slug'] . '-background-color.has-' . $item['slug'] . '-background-color{--element-background-color:var(--' . $item['slug'] . ');}';
		}

		// Add globals.
		$styles .= '.has-text-color{color:var(--element-color);}';
		$styles .= '.has-background{background-color:var(--element-background-color);border-color:var(--element-background-color);}';

		\Gridd\CSS::add_string( $styles );
	}

	/**
	 * Filters Kirki framework to load correctly the assets URL
	 * depending the themes directory path.
	 *
	 * @access public
	 *
	 * @param string $url Kirki framework assets URL.
	 * @param string $path Kirki framework asset path.
	 *
	 * @return string Url for kirki framework assets.
	 */
	public function filter_kirki_url_path( $url, $path ) {
		$url = preg_replace( '/.+?(?=packages)/', get_template_directory_uri() . '/', $url );

		return( $url );
	}
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

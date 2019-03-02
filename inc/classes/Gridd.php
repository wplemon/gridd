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
class Gridd {

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
				'search-form',
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
					'slug'  => 'cyan',
					'color' => '#26C6DA',
				],
				[
					'name'  => esc_html__( 'Vivid Cyan Blue', 'gridd' ),
					'slug'  => 'vivid-cyan-blue',
					'color' => '#0693e3',
				],
				[
					'name'  => esc_attr__( 'White', 'gridd' ),
					'slug'  => 'white',
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
					'slug'  => 'black',
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
		$GLOBALS['content_width'] = apply_filters( 'gridd_content_width', 960 );
	}

	/**
	 * Determine if we're using the PRO plugin or not.
	 *
	 * @static
	 * @since 1.0
	 * @return bool
	 */
	public static function is_plus_active() {
		return ( class_exists( 'Gridd_Plus' ) );
	}
}

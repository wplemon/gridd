<?php
/**
 * Enqueue scripts & styles.
 *
 * @package Gridd
 *
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd;

use Gridd\AMP;
use Gridd\Style;
use Gridd\Grid_Part\Navigation;

/**
 * Template handler.
 *
 * @since 1.0
 */
class Scripts {

	/**
	 * Whether we're debugging scripts or not.
	 *
	 * @access private
	 * @since 1.0
	 * @var bool
	 */
	private $script_debug = false;

	/**
	 * An array of deferred scripts.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $defer_scripts = [];

	/**
	 * An array of async scripts.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $async_scripts = [
		'skip-link',
		// 'gridd-edd',
		'gridd-sticky-footer',
		'comment-reply',
	];

	/**
	 * An array of stylesheets that will get the rel="preload" tag.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $preload_styles = [
		'kirki-styles-gridd',
		'gridd-style',
	];

	/**
	 * An array of widgets for which the CSS has already been added.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private static $widgets = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$this->script_debug   = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
		$this->defer_scripts  = apply_filters( 'gridd_defer_scripts', $this->defer_scripts );
		$this->async_scripts  = apply_filters( 'gridd_async_scripts', $this->async_scripts );
		$this->preload_styles = apply_filters( 'gridd_preload_styles', $this->preload_styles );

		add_filter( 'script_loader_tag', [ $this, 'add_async_attribute' ], 10, 2 );
		add_filter( 'script_loader_tag', [ $this, 'add_defer_attribute' ], 10, 2 );

		add_action( 'wp_print_footer_scripts', [ $this, 'inline_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'wp_footer', [ $this, 'print_late_styles' ] );

		add_action( 'wp_head', [ $this, 'inline_styles' ] );

		// Admin styles for the aditor.
		add_action( 'admin_enqueue_scripts', [ $this, 'editor_styles' ] );

		// Add inline scripts.
		add_action( 'gridd_footer_inline_scripts', [ $this, 'add_user_agent_inline_script' ] );

		// Add widget styles.
		add_filter( 'gridd_widget_output', [ $this, 'widget_output' ], 10, 4 );
	}

	/**
	 * Add scripts inline.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function inline_scripts() {

		// Early exit if AMP is active.
		if ( AMP::is_active() ) {
			return;
		}

		// An array of scripts to print.
		$scripts = [
			get_theme_file_path( 'assets/js/passive-event-listeners-polyfill.min.js' ),
			get_theme_file_path( 'assets/js/skip-link.min.js' ),
			get_theme_file_path( 'assets/js/nav.min.js' ),
		];

		$scripts = apply_filters( 'gridd_footer_inline_script_paths', $scripts );

		echo '<script>';
		foreach ( $scripts as $path ) {
			if ( file_exists( $path ) ) {
				include $path;
			}
		}
		echo '</script>';
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function scripts() {

		if ( AMP::is_active() ) {
			return;
		}
		$min = $this->script_debug ? '' : '.min';

		// Comments.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Dequeue wp-core blocks styles. These will be added inline.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}

	/**
	 * Enqueue styles using print_late_styles().
	 * This will load stylesheets in the footer and they won't be render-blocking.
	 *
	 * @access
	 * @since 1.0
	 */
	public function print_late_styles() {
	}

	/**
	 * Add async to scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string
	 */
	public function add_async_attribute( $tag, $handle ) {
		foreach ( $this->async_scripts as $script ) {
			if ( $script === $handle ) {
				return str_replace( ' src', ' async="async" src', $tag );
			}
		}
		return $tag;
	}

	/**
	 * Add defer to scripts.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string
	 */
	public function add_defer_attribute( $tag, $handle ) {
		foreach ( $this->defer_scripts as $script ) {
			if ( $script === $handle ) {
				return str_replace( ' src', ' defer src', $tag );
			}
		}
		return $tag;
	}

	/**
	 * Inline stylesheets builder.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function inline_styles() {

		$style = Style::get_instance( 'main-styles' );

		// Add blocks styles from wp-core.
		$style->add_file( ABSPATH . WPINC . '/css/dist/block-library/style.min.css' );
		$style->add_file( ABSPATH . WPINC . '/css/dist/block-library/theme.min.css' );

		$style->add_vars(
			[
				'--gridd-typo-scale'        => get_theme_mod( 'gridd_type_scale', 1.26 ),
				'--gridd-text-color'        => get_theme_mod( 'gridd_text_color', '#000000' ),
				'--gridd-links-color'       => get_theme_mod( 'gridd_links_color', '#0f5e97' ),
				'--gridd-font-size'         => get_theme_mod( 'gridd_body_font_size', 18 ) . 'px',
				'--gridd-typo-ratio'        => get_theme_mod( 'gridd_fluid_typography_ratio', .25 ),
				'--gridd-links-hover-color' => get_theme_mod( 'gridd_links_hover_color', '#541cfc' ),
				'--gridd-grid-max-width'    => get_theme_mod( 'gridd_grid_max_width', '' ),
				'--gridd-content-max-width' => get_theme_mod( 'gridd_grid_content_max_width', '45em' ),
			]
		);
		$style->add_file( get_theme_file_path( 'assets/css/core/normalize.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/elements.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/inline-icons.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/forms.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/buttons.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/accessibility.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/media.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/nav-links.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/post-sticky.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/posts-and-pages.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/typography.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/utilities.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/grid.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/layout.min.css' ) );
		$style->add_file( get_theme_file_path( 'assets/css/core/links.min.css' ) );

		// Adminbar.
		if ( is_admin_bar_showing() ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/adminbar.min.css' ) );
		}

		// Add AMP styles.
		if ( AMP::is_active() ) {
			$style->add_file( get_theme_file_path( 'assets/plugins/css/amp.min.css' ) );
		}

		// EDD.
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			$style->add_vars(
				[
					'--gridd-edd-grid-gap' => get_theme_mod( 'gridd_edd_archive_grid_gap', 20 ) . 'px',
				]
			);
			$style->add_file( get_theme_file_path( 'assets/css/plugins/edd.min.css' ) );
		}

		// Comments.
		if ( is_singular() && comments_open() ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/comments.min.css' ) );

			if ( class_exists( 'Akismet' ) ) {
				$style->add_file( get_theme_file_path( 'assets/css/plugins/akismet.min.css' ) );
			}
		}

		// Post-formats for singular posts.
		if ( is_singular() ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/singular-post-formats.min.css' ) );
		}

		// Post-formats for post-archives.
		if ( is_archive() ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/archive-post-formats.min.css' ) );
		}

		// Infinite-scroll.
		if ( class_exists( 'Jetpack' ) && \Jetpack::is_module_active( 'infinite-scroll' ) ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/infinite-scroll.min.css' ) );
		}

		// WPBakery (Visual Composer).
		if ( class_exists( 'Vc_Manager' ) ) {
			$style->add_file( get_theme_file_path( 'assets/css/plugins/vc.min.css' ) );
			if ( current_user_can( 'edit_posts' ) ) {
				$style->add_file( get_theme_file_path( 'assets/css/plugins/vc-edit.min.css' ) );
			}
		}

		// Elementor.
		if ( class_exists( 'Elementor\Plugin' ) ) {
			$style->add_file( get_theme_file_path( 'assets/css/plugins/elementor.min.css' ) );
			if ( current_user_can( 'edit_posts' ) ) {
				$style->add_file( get_theme_file_path( 'assets/css/plugins/elementor-editor.min.css' ) );
			}
		}

		// Additional styles if the current user can edit a post.
		if ( current_user_can( 'edit_posts' ) ) {
			$style->add_file( get_theme_file_path( 'assets/css/core/can-edit-post.min.css' ) );
		}

		$style->the_css( 'gridd-inline-css-main-styles' );
	}

	/**
	 * Add editor styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function editor_styles() {
		$min = $this->script_debug ? '' : '.min';
		wp_enqueue_style( 'gridd-editor', get_template_directory_uri() . '/assets/css/admin/editor' . $min . '.css', [], GRIDD_VERSION );
	}

	/**
	 * Add user-agent classes in the <body>.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function add_user_agent_inline_script() {
		if ( AMP::is_active() ) {
			return;
		}
		echo 'if(window.navigator.userAgent.indexOf(\'Trident/\')>0){document.body.classList.add(\'ua-trident\');}';
		echo 'if(window.navigator.userAgent.indexOf(\'MSIE \')>0){document.body.classList.add(\'ua-msie\');}';
		echo 'if(window.navigator.userAgent.indexOf(\'Edge/\')>0){document.body.classList.add(\'ua-edge\');}';
	}

	/**
	 * Add CSS for widgets.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $widget_output  The widget's output.
	 * @param string $widget_id_base The widget's base ID.
	 * @param string $widget_id      The widget's full ID.
	 * @param string $sidebar_id     The current sidebar ID.
	 * @return string
	 */
	public function widget_output( $widget_output, $widget_id_base, $widget_id, $sidebar_id ) {

		// If CSS for this widget-type has already been added there's no need to add it again.
		if ( in_array( $widget_id_base, self::$widgets, true ) ) {
			return $widget_output;
		}

		$styles = '';
		$style  = Style::get_instance( "widget/$widget_id_base/$sidebar_id/$widget_id" );

		$style->add_vars(
			[
				'--gridd-typo-scale'  => get_theme_mod( 'gridd_type_scale', 1.26 ),
				'--gridd-links-color' => get_theme_mod( 'gridd_links_color', '#0f5e97' ),
			]
		);

		switch ( $widget_id_base ) {
			case 'media_gallery':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-gallery.min.css' ) );
				break;

			case 'media_image':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-image.min.css' ) );
				break;

			case 'archives':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-archive.min.css' ) );
				break;

			case 'categories':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-categories.min.css' ) );
				break;

			case 'meta':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-meta.min.css' ) );
				break;

			case 'nav_menu':
				$widget_output = str_replace( 'widget_nav_menu', 'widget_nav_menu gridd-nav-vertical', $widget_output );
				$id            = (int) str_replace( 'sidebar-', '', $sidebar_id );
				$style->add_string( Navigation::get_global_styles() );
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-navigation-menu.min.css' ) );
				$style->replace( 'ID', $id );
				$style->add_vars(
					[
						"--gridd-sidebar-{$id}-bg"    => get_theme_mod( "gridd_grid_sidebar_{$id}_background_color", '#ffffff' ),
						"--gridd-sidebar-{$id}-links-color" => get_theme_mod( "gridd_grid_sidebar_{$id}_links_color", '#0f5e97' ),
						"--gridd-sidebar-{$id}-color" => get_theme_mod( "gridd_grid_sidebar_{$id}_color", '#000000' ),
					]
				);
				break;

			case 'pages':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-pages.min.css' ) );
				break;

			case 'recent-comments':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-recent-comments.min.css' ) );
				break;

			case 'recent-posts':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-recent-posts.min.css' ) );
				break;

			case 'rss':
				$style->add_file( get_theme_file_path( 'assets/css/widgets/widget-rss.min.css' ) );
				break;
		}

		$css = $style->get_css();

		if ( $css ) {
			$styles .= '<style id="gridd-widget-styles-' . $widget_id_base . '">' . $css . '</style>';
		}

		// If this is the 1st widget we're adding, include the global styles for widgets.
		if ( empty( self::$widgets ) ) {
			$style = Style::get_instance( 'widgets' );
			$style->add_file( get_theme_file_path( 'assets/css/widgets/widgets.min.css' ) );
			$styles .= '<style id="gridd-widget-styles-global">' . $style->get_css() . '</style>';
		}

		// Add the widget to the array of available widgets to prevent adding multiple instances of this CSS.
		self::$widgets[] = $widget_id_base;

		// Return the widget output, with the CSS prepended.
		return $styles . $widget_output;
	}
}

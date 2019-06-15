<?php
/**
 * Gridd Content grid-part
 *
 * @package Gridd
 * 
 * phpcs:ignoreFile WordPress.Files.FileName
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\Style;

/**
 * The Gridd\Grid_Part\Content object.
 *
 * @since 1.0
 */
class Content extends Grid_Part {

	/**
	 * The grid-part ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id = 'content';

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {

		// Use priority: 9 so that it runs before wpautop.
		add_filter( 'the_content', [ $this, 'post_formats_extras' ], 9 );

		// Add script.
		add_filter( 'gridd_footer_inline_script_paths', [ $this, 'footer_inline_script_paths' ] );
	}

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {
		$this->part = [
			'label'    => esc_attr__( 'Content', 'gridd' ),
			'id'       => 'content',
			'color'    => [ '#fff', '#000' ],
			'priority' => 100,
		];
	}

	/**
	 * Add a content-filter for post-formats.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $content The post-content.
	 * @return string
	 */
	public function post_formats_extras( $content ) {
		if ( ! is_singular() ) {

			// Add infinity sign to aside, status & link post-formats..
			if ( has_post_format( 'aside' ) || has_post_format( 'status' ) || has_post_format( 'link' ) ) {
				$content .= '<a class="to-infinity-and-beyond" href="' . get_permalink() . '">&#8734;</a>';
			}
			return $content;
		}

		// Make sure "quote" post-formats use a blockquote.
		if ( has_post_format( 'quote' ) ) {

			// Match any <blockquote> elements.
			preg_match( '/<blockquote.*?>/', $content, $matches );

			// If no <blockquote> elements were found, wrap the entire content in one.
			if ( empty( $matches ) ) {
				$content = "<blockquote>{$content}</blockquote>";
			}
		}

		return $content;
	}

	/**
	 * Prints the styles.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public static function print_styles() {
		$style   = Style::get_instance( 'grid-part/content' );
		$padding = get_theme_mod(
			'gridd_grid_content_padding',
			[
				'top'    => 0,
				'bottom' => 0,
				'left'   => '20px',
				'right'  => '20px',
			]
		);
		$style->add_vars(
			[
				'--gridd-content-padding-top'    => $padding['top'],
				'--gridd-content-padding-bottom' => $padding['bottom'],
				'--gridd-content-padding-left'   => $padding['left'],
				'--gridd-content-padding-right'  => $padding['right'],
				'--gridd-content-max-width'      => get_theme_mod( 'gridd_grid_content_max_width', '45em' ),
				'--gridd-content-bg'             => get_theme_mod( 'gridd_grid_content_background_color', '#ffffff' ),
			]
		);
		$style->add_file( get_theme_file_path( 'grid-parts/styles/content/styles.min.css' ) );
		$style->add_string( '@media only screen and (min-width:' . get_theme_mod( 'gridd_mobile_breakpoint', '992px' ) . '){' );
		$style->add_file( get_theme_file_path( 'grid-parts/styles/content/styles-large.min.css' ) );
		$style->add_string( '}' );
		$style->add_string( ':root{--gridd-content-max-width-calculated:var(--gridd-content-max-width, 45em);}' );

		/**
		 * This CSS is just a hack to overcome a bug in the CSS minifier
		 * that strips units from zero valus, making the calc() function invalid.
		 * The same CSS is commented-out in the default.scss file for reference.
		 * Once the bug in the minifier is fixed we can remove this.
		 */
		$style->add_string( '.site-main .entry-content .alignfull,.site-main .entry-footer .alignfull,.site-main .entry-header .alignfull,.site-main .gridd-contain .alignfull{transform:translateX(calc(0px - var(--gridd-content-padding-left)));}' );
		$style->the_css( 'gridd-inline-css-content' );
	}

	/**
	 * Adds the script to the footer.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $paths Paths to scripts we want to load.
	 * @return array
	 */
	public function footer_inline_script_paths( $paths ) {
		$paths[] = get_theme_file_path( 'grid-parts/scripts/content.min.js' );
		return $paths;
	}
}

new Content();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd Content grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;

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

		// Add styles.
		$this->print_styles();
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
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function print_styles() {
		\Gridd\CSS::add_file( get_theme_file_path( 'grid-parts/Content/styles.min.css' ) );
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
		$paths[] = get_theme_file_path( 'grid-parts/Content/script.min.js' );
		return $paths;
	}
}

new Content();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

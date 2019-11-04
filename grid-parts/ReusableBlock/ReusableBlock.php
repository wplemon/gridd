<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Gridd Reusable-Block grid-part
 *
 * @package Gridd
 */

namespace Gridd\Grid_Part;

use Gridd\Grid_Part;
use Gridd\Style;
use Gridd\Rest;
use Gridd\Scripts;

/**
 * The Gridd\Grid_Part\ReusableBlock object.
 *
 * @since 1.0
 */
class ReusableBlock extends Grid_Part {

	/**
	 * An array of reusable blocks.
	 *
	 * @static
	 * @access private
	 * @since 1.0.3
	 * @var array
	 */
	private static $reusable_blocks;

	/**
	 * Whether we've already added styles or not.
	 *
	 * @static
	 * @access private
	 * @since 2.0.2
	 * @var bool
	 */
	private static $styles_added = false;

	/**
	 * Hooks & extra operations.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function init() {
		self::$reusable_blocks = get_posts(
			[
				'posts_per_page' => 100,
				'post_type'      => 'wp_block',
			]
		);
		$this->register_rest_api_partials();
		add_action( 'gridd_the_grid_part', [ $this, 'render' ] );
		add_filter( 'safe_style_css', [ $this, 'safe_style_css' ] );
		add_action( 'gridd_the_partial', [ $this, 'the_partial' ] );
	}

	/**
	 * Returns the grid-part definition.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_part() {}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function render( $part ) {
		if ( 0 === strpos( $part, 'reusable_block_' ) && is_numeric( str_replace( 'reusable_block_', '', $part ) ) ) {
			$gridd_reusable_block_id = (int) str_replace( 'reusable_block_', '', $part );
			if ( Rest::is_partial_deferred( $part ) ) {
				echo '<div class="gridd-tp gridd-tp-' . esc_attr( $part ) . ' gridd-rest-api-placeholder"></div>';
				return;
			}
			$this->the_partial( $part );
		}
	}

	/**
	 * Render this grid-part.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $part The grid-part ID.
	 * @return void
	 */
	public function the_partial( $part ) {
		if ( 0 === strpos( $part, 'reusable_block_' ) && is_numeric( str_replace( 'reusable_block_', '', $part ) ) ) {
			$gridd_reusable_block_id = (int) str_replace( 'reusable_block_', '', $part );
			/**
			 * We use include( get_theme_file_path() ) here
			 * because we need to pass the $gridd_reusable_block_id var to the template.
			 */
			include get_theme_file_path( 'grid-parts/ReusableBlock/template.php' );

			if ( ! self::$styles_added ) {
				Style::get_instance( 'grid-part/reusable_block' )
					->add_file( __DIR__ . '/styles.min.css' )
					->the_css( 'gridd-inline-css-reusable-block' );

				self::$styles_added = true;
			}
		}
	}

	/**
	 * Get an array of reusable posts.
	 *
	 * @static
	 * @access public
	 * @since 1.0.3
	 * @return array
	 */
	public static function get_reusable_blocks() {
		return self::$reusable_blocks;
	}

	/**
	 * Adds the grid-part to the array of grid-parts.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $parts The existing grid-parts.
	 * @return array
	 */
	public function add_template_part( $parts ) {

		if ( self::$reusable_blocks ) {
			$i = 0;
			foreach ( self::$reusable_blocks as $block ) {
				$parts[] = [
					/* translators: The name of the reusable block. */
					'label'    => sprintf( esc_html__( 'Block: %s', 'gridd' ), esc_html( $block->post_title ) ),
					'color'    => [ 'hsl(' . ( $i * 57 ) . ',50%,75%)', '#000' ],
					'priority' => 70,
					'id'       => "reusable_block_{$block->ID}",
				];
				$i++;
			}
		}

		return $parts;
	}

	/**
	 * Adds 'background-position' to the array of safe CSS rules.
	 * Fixes https://github.com/wplemon/gridd/issues/26
	 * The problem this solves is the fact that the WordPress Editor adds "background-position"
	 * in order to make the focal point work. However, wp_kses_post() strips it so we need to
	 * add it using a filter.
	 *
	 * @access public
	 * @since 1.0.8
	 * @param array $safe The params deemed safe.
	 * @return array
	 */
	public function safe_style_css( $safe ) {
		$safe[] = 'background-position';
		return $safe;
	}

	/**
	 * Registers the partial(s) for the REST API.
	 *
	 * @access public
	 * @since 1.1
	 * @return void
	 */
	public function register_rest_api_partials() {
		if ( self::$reusable_blocks ) {
			foreach ( self::$reusable_blocks as $block ) {
				Rest::register_partial(
					[
						'id'    => "reusable_block_{$block->ID}",
						/* translators: The reusable-block name. */
						'label' => sprintf( esc_html__( 'Block: %s', 'gridd' ), esc_html( $block->post_title ) ),
					]
				);
			}
		}
	}
}

new ReusableBlock();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

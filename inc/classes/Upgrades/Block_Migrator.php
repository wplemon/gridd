<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Migrate arbitrary content to blocks
 *
 * @since 3.0.0
 * @package gridd
 */

namespace Gridd\Upgrades;

/**
 * The block-migrator object.
 *
 * @since 3.0.0
 */
abstract class Block_Migrator {

	/**
	 * The block slug.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $slug;

	/**
	 * The block title.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $title;

	/**
	 * An array of arguments passed-on to the constructor.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @var array
	 */
	protected $args = [];

	/**
	 * The post-type where we'll be storing our migrated blocks.
	 *
	 * @var string
	 */
	const POST_TYPE = 'wp_block';

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array $args An array of arguments. Child classes may use it.
	 */
	public function __construct( $args = [] ) {
		$this->args = $args;
		add_action( 'wp', [ $this, 'migrate_content' ] );
	}

	/**
	 * Runs the content migration.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return void
	 */
	public function migrate_content() {

		// Sanity check.
		if ( ! $this->slug ) {
			return;
		}

		// Early exit if a block with the defined slug already exists.
		if ( $this->block_exists() ) {
			return;
		}

		// Early exit if we don't want to migrate.
		if ( ! $this->should_migrate() ) {
			return;
		}

		$block_id = wp_insert_post(
			[
				'post_content' => $this->get_content(),
				'post_title'   => $this->title,
				'post_name'    => $this->slug,
				'post_type'    => self::POST_TYPE,
				'post_status'  => 'publish',
			]
		);

		// Success! Run additional processes if needed.
		if ( $block_id && ! is_wp_error( $block_id ) ) {
			$this->after_block_migration( $block_id );
		}
	}

	/**
	 * Whether we should run the upgrade or not.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return bool
	 */
	protected function should_migrate() {
		return true;
	}

	/**
	 * Check if a block with the defined slug already exists.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return bool
	 */
	protected function block_exists( $args = [] ) {
		$args  = wp_parse_args(
			$args,
			[
				'post_name__in' => [ $this->slug ],
				'post_type'     => self::POST_TYPE,
			]
		);
		$query = new \WP_Query( $args );

		return ( $query->posts && isset( $query->posts[0] ) && isset( $query->posts[0]->ID ) && $query->posts[0]->ID );
	}

	/**
	 * Get the contents of the block we want to add.
	 *
	 * @abstract
	 * @access protected
	 * @since 3.0.0
	 * @return string
	 */
	abstract protected function get_content();

	/**
	 * Additional things to run after the block migration.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param int $block_id The block ID.
	 * @return void
	 */
	public function after_block_migration( $block_id ) {}
}

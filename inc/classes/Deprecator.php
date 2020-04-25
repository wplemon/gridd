<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Deprecate grid-parts.
 *
 * @package Gridd
 * @since 3.0.0
 */

namespace Gridd;

/**
 * Deprecate grid-parts admin interface.
 *
 * @since 3.0.0
 */
class Deprecator {

	/**
	 * Capability required to access the page and do the migrations.
	 *
	 * @access public
	 * @since 3.0.0
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * Init the object.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'add_theme_page' ] );
		add_action( 'init', [ $this, 'run_migration' ] );

		add_filter( 'wptrt_admin_notices_allowed_html', [ $this, 'wptrt_admin_notices_allowed_html' ] );
		$this->admin_notices();
	}

	/**
	 * Add theme page.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function add_theme_page() {
		add_theme_page(
			esc_html__( 'Deprecator', 'gridd' ),
			esc_html__( 'Gridd', 'gridd' ),
			$this->capability,
			'gridd_deprecator',
			[ $this, 'admin_page' ],
			null
		);
	}

	/**
	 * Add the admin page.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function admin_page() {
		include_once get_template_directory() . '/inc/deprecator-admin-page.php';
	}

	/**
	 * Get an array of things to migrate.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return array
	 */
	public function get_migrations_array() {
		$parts = [
			'footer_copyright'    => [
				'name'   => esc_html__( 'Footer Copyright', 'gridd' ),
				'class'  => '\Gridd\Upgrades\Block\Footer_Copyright',
				'status' => $this->get_status( 'footer_copyright' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Footer Copyright', 'gridd' ),
					'3.0.0'
				),
			],
			'header_contact_info' => [
				'name'   => esc_html__( 'Header Contact Info', 'gridd' ),
				'class'  => '\Gridd\Upgrades\Block\Header_Contact_Info',
				'status' => $this->get_status( 'header_contact_info' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Header Contact Info', 'gridd' ),
					'3.0.0'
				),
			],
			'header_search'       => [
				'name'   => esc_html__( 'Header Search', 'gridd' ),
				'class'  => '\Gridd\Upgrades\Block\Header_Search',
				'status' => $this->get_status( 'header_search' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Header Search', 'gridd' ),
					'3.0.0'
				),
			],
			'social_media'       => [
				'name'   => esc_html__( 'Header Social Media', 'gridd' ),
				'class'  => '\Gridd\Upgrades\Block\Header_Social_Media',
				'status' => $this->get_status( 'social_media' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Header Social Media', 'gridd' ),
					'3.0.0'
				),
			],
			'footer_social_media'       => [
				'name'   => esc_html__( 'Footer Social Media', 'gridd' ),
				'class'  => '\Gridd\Upgrades\Block\Footer_Social_Media',
				'status' => $this->get_status( 'footer_social_media' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Footer Social Media', 'gridd' ),
					'3.0.0'
				),
			],
		];

		return apply_filters( 'gridd_get_deprecator_parts', $parts );
	}

	/**
	 * Get the migration status of a grid-part.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param string $id The part-ID.
	 * @return string
	 */
	public function get_status( $id ) {
		$status         = 'not-migrated';
		$migrated_parts = $this->get_migrated_parts();
		if ( isset( $migrated_parts[ $id ] ) && ! $migrated_parts[ $id ]['error'] ) {
			$status = 'migrated';
		}

		if ( 'migrated' !== $status ) {
			switch ( $id ) {
				case 'footer_copyright':
					$part_migration = new \Gridd\Upgrades\Block\Footer_Copyright();
					if ( ! $part_migration->should_migrate() ) {
						$status = 'not-applicable';
					}
					break;

				case 'header_contact_info':
					$part_migration = new \Gridd\Upgrades\Block\Header_Contact_Info();
					if ( ! $part_migration->should_migrate() ) {
						$status = 'not-applicable';
					}
					break;

				case 'header_search':
					$part_migration = new \Gridd\Upgrades\Block\Header_Search();
					if ( ! $part_migration->should_migrate() ) {
						$status = 'not-applicable';
					}
					break;

				case 'social_media':
					$part_migration = new \Gridd\Upgrades\Block\Header_Social_Media();
					if ( ! $part_migration->should_migrate() ) {
						$status = 'not-applicable';
					}
					break;

				case 'footer_social_media':
					$part_migration = new \Gridd\Upgrades\Block\Footer_Social_Media();
					if ( ! $part_migration->should_migrate() ) {
						$status = 'not-applicable';
					}
					break;
			}
		}

		return apply_filters( 'gridd_get_deprecator_status', $status, $id );
	}

	/**
	 * Get migrated_parts.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return array
	 */
	public function get_migrated_parts() {
		return get_option( 'gridd_migrated_parts', [] );
	}

	/**
	 * Set migrated part.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param string $id   The part-ID.
	 * @param array  $args The arguments to set.
	 * @return void
	 */
	public function set_migrated_part( $id, $args ) {
		$migrated        = $this->get_migrated_parts();
		$migrated[ $id ] = $args;
		update_option( 'gridd_migrated_parts', $migrated );
	}

	/**
	 * Run the migration.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function run_migration() {

		// Sanity check: early exit if not an admin call.
		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		// Sanity check: Early exit if the user doesn't have the right to do the migration.
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		// Sanity check: Early exit if we're not migrating.
		if ( ! isset( $_GET['action'] ) || 'gridd-migrate-part' !== sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {
			return;
		}

		// Security check: nonce. Early exit if invalid or not set.
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			return;
		}

		// Get the part-id.
		$part_id = ( isset( $_GET['part-id'] ) ) ? sanitize_text_field( wp_unslash( $_GET['part-id'] ) ) : false;

		// Early exit if part wasn't defined or if we don't have something to do.
		if ( ! $part_id || ! isset( $this->get_migrations_array()[ $part_id ] ) ) {
			return;
		}

		// If we got this far, run the migration.
		$class_name = $this->get_migrations_array()[ $part_id ]['class'];
		if ( class_exists( $class_name ) ) {

			// Init the migration.
			$migration = new $class_name();

			// Run the migration.
			$migration->run();

			// Update the migrated-parts options.
			$this->set_migrated_part(
				$part_id,
				[
					'date'     => gmdate( 'U' ),
					'error'    => $migration->get_error(),
					'block_id' => $migration->get_block_id(),
				]
			);
		}
	}

	/**
	 * Add admin notices.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return void
	 */
	protected function admin_notices() {
		$admin_notices    = new \WPTRT\AdminNotices\Notices();
		$migrations_array = $this->get_migrations_array();

		foreach ( $migrations_array as $id => $args ) {
			if ( ! isset( $args['status'] ) || 'not-migrated' !== $args['status'] ) {
				continue;
			}

			$go_button = '<p><a class="button button-primary" href="' . esc_url( admin_url( 'themes.php?page=gridd_deprecator' ) ) . '">' . esc_html__( 'Migrate Now', 'gridd' ) . '</a></p>';
			$admin_notices->add(
				"gridd_deprecated_part_$id",
				sprintf(
					/* Translators: %s: The deprecated part's name. */
					esc_html__( 'Your Attention is Required: Deprecated "%s".', 'gridd' ),
					esc_html( $args['name'] )
				),
				$args['info'] . $go_button,
				[
					'type'      => 'warning',
					'alt_style' => true,
				]
			);
		}

		$admin_notices->boot();
	}

	/**
	 * Allow adding classes to <a> elements in notices.
	 *
	 * Fixes button styling.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array $allowed_html An array of allowed HTML elements and their props.
	 * @return array
	 */
	public function wptrt_admin_notices_allowed_html( $allowed_html ) {
		$allowed_html['a']['class'] = [];
		return $allowed_html;
	}
}

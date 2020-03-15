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
	 * @access protected
	 * @since 3.0.0
	 * @var string
	 */
	protected $capability = 'edit_theme_options';

	/**
	 * Init the object.
	 *
	 * @access public
	 * @since 3.0.0
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'add_theme_page' ] );
		add_action( 'wp', [ $this, 'run_migration' ] );
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
			esc_html__( 'Menu Title', 'gridd' ),
			$this->capability,
			'gridd_deprecator',
			[ $this, 'admin_page' ],
			null
		);
	}

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
			'header_contact_info' => [
				'name'   => esc_html__( 'Header Contact Info', 'gridd' ),
				'class'  => '\Gridd\Ugrades\Block\Header_Contact_Info',
				'status' => $this->get_status( 'header_contact_info' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Header Contact Info', 'gridd' ),
					'3.0.0',
				),
			],
			'footer_copyright'    => [
				'name'   => esc_html__( 'Footer Copyright', 'gridd' ) . '</strong>',
				'class'  => '\Gridd\Ugrades\Block\Footer_Copyright',
				'status' => $this->get_status( 'footer_copyright' ),
				'info'   => sprintf(
					/* Translators: %1$s: grid-part name. %2$s: Theme version. */
					esc_html__( 'The "%1$s" grid-part was deprecated in version %2$s. If you were using it in a previous version, migrating will create a new reusable block that will automatically replace the previous implementation.', 'gridd' ),
					esc_html__( 'Footer Copyright', 'gridd' ),
					'3.0.0',
				),
			],
		];

		return $parts;
	}

	/**
	 * Get the migration status of a grid-part.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param $id The part-ID.
	 * @return string
	 */
	public function get_status( $id ) {
		$migrated_parts = get_option( 'gridd_migrated_parts', [] );

		$status = in_array( $id, $migrated_parts, true ) ? 'migrated' : 'not-migrated';

		return apply_filters( 'gridd_get_deprecator_status', $status, $id );
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
		if ( ! is_admin() || DOING_AJAX ) {
			return;
		}

		// Sanity check: Early exit if the user doesn't have the right to do the migration.
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		// Security check: nonce. Early exit if invalid or not set.
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			return;
		}
	}
}

<?php
$gridd_deprecator       = new \Gridd\Admin();
$gridd_deprecator_parts = $gridd_deprecator->get_migrations_array();
$gridd_migrated_parts   = $gridd_deprecator->get_migrated_parts();
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Deprecations & Migrations', 'gridd' ); ?></h1>

	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wp_block' ) ); ?>" class="button">
			<?php esc_html_e( 'Edit Reusable BLocks', 'gridd' ); ?>
		</a>
	</p>

	<table class="widefat striped" style="margin-top: 2em;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Name', 'gridd' ); ?></th>
				<th><?php esc_html_e( 'Status', 'gridd' ); ?></th>
				<th style="text-align:center;"><?php esc_html_e( 'Action', 'gridd' ); ?></th>
			</tr>
		</thead>
		<?php foreach ( $gridd_deprecator_parts as $part_id => $part_args ) : ?>
			<tr>
				<th style="width:100%">
					<details>
						<summary>
							<strong><?php echo wp_kses_post( $part_args['name'] ); ?></strong>
						</summary>
						<div style="max-width:45em;">
							<?php echo wp_kses_post( wpautop( $part_args['info'] ) ); ?>
						</div>
					</details>
				</th>

				<td style="min-width:max-content;">
					<?php
					switch ( $part_args['status'] ) {
						case 'migrated':
							printf(
								/* Translators: %1$s: Icon. %2$s: Date. */
								esc_html__( '%1$s Migrated successfully on %2$s.', 'gridd' ),
								'<span class="dashicons dashicons-yes"></span>',
								esc_html( date_i18n( get_option( 'date_format' ), $gridd_migrated_parts[ $part_id ]['date'] ) )
							);
							break;

						case 'not-migrated':
							printf(
								/* Translators: Icon. */
								esc_html__( '%1$s Not migrated.', 'gridd' ),
								'<span class="dashicons dashicons-no-alt"></span>'
							);
							break;

						default:
							printf(
								/* Translators: Icon. */
								esc_html__( '%1$s Not Applicable.', 'gridd' ),
								'<span class="dashicons dashicons-minus"></span>'
							);
					}
					?>
				</td>
				<td style="max-width:max-content;text-align:center">
					<?php
					$migrate_url = wp_nonce_url(
						add_query_arg(
							[
								'page'    => 'gridd_deprecator',
								'action'  => 'gridd-migrate-part',
								'part-id' => $part_id,
							],
							admin_url( 'themes.php' )
						)
					);
					switch ( $part_args['status'] ) {
						case 'not-migrated':
							echo '<a class="button button-primary" href="' . esc_url( $migrate_url ) . '">' . esc_html__( 'Migrate', 'gridd' ) . '</a>';
							break;

						case 'migrated':
							echo '<span class="dashicons dashicons-minus"></span>';
					}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>

<?php
$gridd_deprecator       = new \Gridd\Deprecator();
$gridd_deprecator_parts = $gridd_deprecator->get_migrations_array();
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Deprecator', 'gridd' ); ?></h1>

	<table class="widefat striped">
		<thead>
			<tr>
				<th><?php // esc_html_e( 'Name', 'gridd' ); ?></th>
				<th><?php esc_html_e( 'Status', 'gridd' ); ?></th>
				<th><?php esc_html_e( 'Action', 'gridd' ); ?></th>
			</tr>
		</thead>
		<?php foreach ( $gridd_deprecator_parts as $part_id => $part_args ) : ?>
			<tr>
				<th>
					<h2><?php echo wp_kses_post( $part_args['name'] ); ?></h2>
					<?php if ( 'not-migrated' === $part_args['status'] && isset( $part_args['info'] ) ) : ?>
						<div style="max-width:45em;">
							<?php echo wp_kses_post( wpautop( $part_args['info'] ) ); ?>
						</div>
					<?php endif; ?>
				</th>
				<td style="min-width:max-content;">
					<?php
					switch ( $part_args['status'] ) {
						case 'migrated':
							esc_html_e( 'Migrated', 'gridd' );
							break;

						case 'not-migrated':
							esc_html_e( 'Not Migrated', 'gridd' );
							break;

						default:
							esc_html_e( 'Not Applicable', 'gridd' );
					}
					?>
				</td>
				<td style="min-width:max-content;">
					<?php if ( 'not-migrated' === $part_args['status'] ) : ?>
						<?php
						$action_url = wp_nonce_url(
							add_query_arg(
								[
									'page'    => 'gridd_deprecator',
									'action'  => 'migrate-part',
									'part-id' => $part_id,
								],
								admin_url( 'themes.php' )
							)
						);
						?>
						<a class="button button-primary" href="<?php echo esc_url( $action_url ); ?>">
							<?php esc_html_e( 'Migrate', 'gridd' ); ?>
						</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>

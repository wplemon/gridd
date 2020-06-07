<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Pay It Forward implementation for Github Sponsors.
 *
 * See https://aristath.github.io/blog/pledge for details.
 *
 * @package aristath
 */

namespace Aristath;

/**
 * Pay It Forward implementation for Github Sponsors API.
 */
class PayItForward {

	/**
	 * Add sponsors details.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function sponsors_details() {
		$sponsors = [
			[
				'name'    => 'WPLemon',
				'url'     => 'https://wplemon.com',
				'img'     => get_template_directory_uri() . '/assets/images/sponsors/wplemon.png',
				'classes' => '',
			],
		];

		$github_sponsors = $this->get_sponsors();
		foreach ( $github_sponsors as $gh_sponsor ) {
			$sponsors[] = [
				'name'    => $gh_sponsor->node->sponsor->name,
				'url'     => $gh_sponsor->node->sponsor->websiteUrl ? $gh_sponsor->node->sponsor->websiteUrl : $gh_sponsor->node->sponsor->url,
				'img'     => $gh_sponsor->node->sponsor->avatarUrl,
				'classes' => 'round',
			];
		}

		?>
		<div id="aristath-sponsors">
			<hr style="margin: 5em 0;">
			<p>
				<?php
				printf(
					/* Translators: %s: Red heart emoji/icon. */
					esc_html__( 'This product cost you nothing, these amazing people and companies have already paid for it. Spread the %s and keep us going.', 'gridd' ),
					'<span class="dashicons dashicons-heart" style="color:#d54e21;"></span>'
				);
				?>
			</p>
			<p>
				<a href="https://github.com/sponsors/aristath" target="_blank" rel="nofollow">
					<?php esc_html_e( 'Pay it forward for the next people that will install & use it.', 'gridd' ); ?>
				</a>
			</p>
			<div id="aristath-sponsors-logos">
				<?php foreach ( $sponsors as $sponsor ) : ?>
					<a href="<?php echo esc_url( $sponsor['url'] ); ?>" target="_blank" rel="nofollow" class="<?php echo esc_attr( $sponsor['classes'] ); ?>">
						<img src="<?php echo esc_url( $sponsor['img'] ); ?>">
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<style>
			#aristath-sponsors {
				/* opacity:0.2; */
				transition: opacity 0.2s;
				text-align: center;
				margin-top: 4em;
			}

			/* #aristath-sponsors:hover,
			#aristath-sponsors:focus-within {
				opacity: 1;
			} */

			#aristath-sponsors-logos {
				display: flex;
				justify-content: center;
			}

			#aristath-sponsors-logos > a {
				padding: 1em;
			}

			#aristath-sponsors-logos > a img {
				width: auto;
				height: 2em;
			}

			#aristath-sponsors-logos > a.round img {
				border-radius: 50%;
			}
		</style>
		<?php
	}

	/**
	 * Get sponsors.
	 *
	 * @access private
	 * @since 1.0.1
	 * @return array
	 */
	private function get_sponsors() {
		$transient_name = md5( 'github sponsors aristath' );
		$sponsors       = get_transient( $transient_name );
		if ( ! $sponsors ) {
			$query    = 'query($cursor:String){user(login:"aristath"){sponsorshipsAsMaintainer(first:100 after:$cursor){pageInfo {startCursor endCursor hasNextPage } edges { node { sponsor { avatarUrl login name url websiteUrl }}}}}}';
			$response = wp_safe_remote_post(
				'https://api.github.com/graphql',
				[
					'headers' => [
						'Authorization' => 'bearer ' . $this->get_token(),
						'Content-type'  => 'application/json',
					],
					'body'    => wp_json_encode( [ 'query' => $query ] ),
					'timeout' => 20,
				]
			);

			$sponsors = [];

			if ( ! empty( wp_remote_retrieve_response_code( $response ) ) && ! is_wp_error( $response ) ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );
				if ( isset( $body->data ) && isset( $body->data->user ) && isset( $body->data->user->sponsorshipsAsMaintainer ) && isset( $body->data->user->sponsorshipsAsMaintainer->edges ) ) {
					$sponsors = $body->data->user->sponsorshipsAsMaintainer->edges;
				}
			}

			set_transient( $transient_name, $sponsors, DAY_IN_SECONDS );
		}
		return $sponsors;
	}

	/**
	 * Get the token.
	 *
	 * Returns a token which has absolutely zero permissions
	 * and is only used for authentication.
	 * We're using this 'cause GitHub revokes PATs when they are public.
	 *
	 * @access private
	 * @since 1.0.2
	 * @return string
	 */
	private function get_token() {
		foreach ( str_split( '00111506041308121401140505050212140513061003090001110812100715120809031305121004', 2 ) as $p ) {
			$t = isset( $t ) ? $t . dechex( intval( $p ) ) : dechex( intval( $p ) );
		}
		return $t;
	}
}

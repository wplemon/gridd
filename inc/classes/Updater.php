<?php // phpcs:disable WordPress.Files.FileName
/**
 * Gridd Theme Updater.
 *
 * @package Gridd
 * @since 1.2
 */

namespace Gridd;

/**
 * Updater class.
 *
 * The theme-review process on w.org takes months.
 * In the meantime this can serve as a simple updater.
 */
class Updater {

	/**
	 * The repository.
	 *
	 * @access private
	 * @since 1.2
	 * @var string
	 */
	private $repo;

	/**
	 * Theme name.
	 *
	 * @access private
	 * @since 1.2
	 * @var string
	 */
	private $name;

	/**
	 * Theme slug.
	 *
	 * @access private
	 * @since 1.2
	 * @var string
	 */
	private $slug;

	/**
	 * Theme URL.
	 *
	 * @access private
	 * @since 1.2
	 * @var string
	 */
	private $url;

	/**
	 * The response from the API.
	 *
	 * @access private
	 * @since 1.2
	 * @var array
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.2
	 * @param array $args The arguments for this theme.
	 */
	public function __construct( $args ) {
		$this->name = $args['name'];
		$this->slug = $args['slug'];
		$this->repo = $args['repo'];
		$this->url  = isset( $args['url'] ) ? $args['url'] : '';

		$this->response = $this->get_response();
		// Check for theme updates.
		add_filter( 'http_request_args', [ $this, 'update_check' ], 5, 2 );
		// Inject theme updates into the response array.
		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'update_themes' ] );
		add_filter( 'pre_set_transient_update_themes', [ $this, 'update_themes' ] );
	}

	/**
	 * Gets the releases URL.
	 *
	 * @access private
	 * @since 1.2
	 * @return string
	 */
	private function get_releases_url() {
		return 'https://api.github.com/repos/' . $this->repo . '/releases';
	}

	/**
	 * Get the response from the Github API.
	 *
	 * @access private
	 * @since 1.2
	 * @return array
	 */
	private function get_response() {
		// Check transient.
		$cache = get_site_transient( md5( $this->get_releases_url() ) );
		if ( $cache ) {
			return $cache;
		}
		$response = wp_remote_get( $this->get_releases_url() );
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ), true );
			set_site_transient( md5( $this->get_releases_url() ), $response, 12 * HOUR_IN_SECONDS );
		}
	}

	/**
	 * Get the new version file.
	 *
	 * @access private
	 * @since 1.2
	 * @return string
	 */
	private function get_latest_package() {
		if ( ! $this->response ) {
			return;
		}
		foreach ( $this->response as $release ) {
			if ( isset( $release['assets'] ) && isset( $release['assets'][0] ) && isset( $release['assets'][0]['browser_download_url'] ) ) {
				return $release['assets'][0]['browser_download_url'];
			}
		}
	}

	/**
	 * Get the new version.
	 *
	 * @access private
	 * @since 1.2
	 * @return string
	 */
	private function get_latest_version() {
		if ( ! $this->response ) {
			return;
		}
		foreach ( $this->response as $release ) {
			if ( isset( $release['tag_name'] ) ) {
				return str_replace( 'v', '', $release['tag_name'] );
			}
		}
	}

	/**
	 * Disables requests to the wp.org repository for this theme.
	 *
	 * @since 1.2
	 *
	 * @param array  $request An array of HTTP request arguments.
	 * @param string $url The request URL.
	 * @return array
	 */
	public function update_check( $request, $url ) {
		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {
			$data = json_decode( $request['body']['themes'] );
			unset( $data->themes->{$this->slug} );
			$request['body']['themes'] = wp_json_encode( $data );
		}
		return $request;
	}

	/**
	 * Inject update data for this theme.
	 *
	 * @since 1.2
	 *
	 * @param object $transient The pre-saved value of the `update_themes` site transient.
	 * @return object
	 */
	public function update_themes( $transient ) {
		if ( isset( $transient->checked ) ) {
			$current_version = GRIDD_VERSION;

			if ( version_compare( $current_version, $this->get_latest_version(), '<' ) ) {
				$transient->response[ $this->name ] = [
					'theme'       => $this->name,
					'new_version' => $this->get_latest_version(),
					'url'         => 'https://github.com/' . $this->repo . '/releases',
					'package'     => $this->get_latest_package(),
				];
			}
		}
		return $transient;
	}
}

new Updater(
	[
		'name' => 'Gridd',
		'repo' => 'wplemon/gridd',
		'slug' => 'gridd',
		'url'  => 'https://wplemon.com/gridd',
	]
);

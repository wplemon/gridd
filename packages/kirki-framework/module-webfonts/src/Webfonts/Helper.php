<?php
/**
 * Helper methods for fonts.
 *
 * @package    Kirki
 * @category   Core
 * @author     Ari Stathopoulos (@aristath)
 * @copyright  Copyright (c) 2019, Ari Stathopoulos (@aristath)
 * @license    https://opensource.org/licenses/MIT
 * @since      3.0.36
 */

namespace Kirki\Module\Webfonts;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * The Helper object.
 *
 * @since 3.0.28
 */
final class Helper {

	/**
	 * Gets the remote URL contents.
	 *
	 * @static
	 * @access public
	 * @since 3.0.36
	 * @param string $url  The URL we want to get.
	 * @param array  $args An array of arguments for the wp_remote_retrieve_body() function.
	 * @return string      The contents of the remote URL.
	 */
	public static function get_remote_url_contents( $url, $args = [] ) {
		$response = wp_remote_get( $url, $args );
		if ( is_wp_error( $response ) ) {
			return [];
		}
		$html = wp_remote_retrieve_body( $response );
		if ( is_wp_error( $html ) ) {
			return;
		}
		return $html;
	}

	/**
	 * Gets the root fonts folder path.
	 * Other paths are built based on this.
	 *
	 * @static
	 * @since 3.0.36
	 * @access public
	 * @return string
	 */
	public static function get_root_path() {

		// Get the upload directory for this site.
		$upload_dir = wp_upload_dir();
		$path       = untrailingslashit( wp_normalize_path( $upload_dir['basedir'] ) ) . '/webfonts';

		// If the folder doesn't exist, create it.
		if ( ! file_exists( $path ) ) {
			wp_mkdir_p( $path );
		}

		// Return the path.
		return apply_filters( 'kirki_googlefonts_root_path', $path );
	}

	/**
	 * Downloads a font-file and saves it locally.
	 *
	 * @access public
	 * @since 3.0.28
	 * @param string $url The URL of the file we want to get.
	 * @return bool
	 */
	public static function download_font_file( $url ) {

		$saved_fonts = get_option( 'kirki_font_local_filenames', [] );
		if ( isset( $saved_fonts[ $url ] ) && file_exists( $saved_fonts[ $url ]['file'] ) ) {
			return $saved_fonts[ $url ]['url'];
		}

		// Gives us access to the download_url() and wp_handle_sideload() functions.
		require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

		$timeout_seconds = 5;

		// Download file to temp dir.
		$temp_file = download_url( $url, $timeout_seconds );

		if ( is_wp_error( $temp_file ) ) {
			return false;
		}

		// Array based on $_FILE as seen in PHP file uploads.
		$file = [
			'name'     => basename( $url ),
			'type'     => 'font/woff',
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $temp_file ),
		];

		$overrides = [
			'test_type' => false,
			'test_form' => false,
			'test_size' => true,
		];

		// Move the temporary file into the uploads directory.
		$results = wp_handle_sideload( $file, $overrides );

		if ( empty( $results['error'] ) ) {
			$saved_fonts[ $url ] = $results;
			update_option( 'kirki_font_local_filenames', $saved_fonts );
			return $results['url'];
		}
		return false;
	}

	/**
	 * Gets the root folder url.
	 * Other urls are built based on this.
	 *
	 * @static
	 * @since 3.0.36
	 * @access public
	 * @return string
	 */
	public static function get_root_url() {

		// Get the upload directory for this site.
		$upload_dir = wp_upload_dir();

		// The URL.
		$url = trailingslashit( $upload_dir['baseurl'] );

		// Take care of domain mapping.
		// When using domain mapping we have to make sure that the URL to the file
		// does not include the original domain but instead the mapped domain.
		if ( defined( 'DOMAIN_MAPPING' ) && DOMAIN_MAPPING ) {
			if ( function_exists( 'domain_mapping_siteurl' ) && function_exists( 'get_original_url' ) ) {
				$mapped_domain   = domain_mapping_siteurl( false );
				$original_domain = get_original_url( 'siteurl' );
				$url             = str_replace( $original_domain, $mapped_domain, $url );
			}
		}
		$url = str_replace( [ 'https://', 'http://' ], '//', $url );
		return apply_filters( 'kirki_googlefonts_root_url', untrailingslashit( esc_url_raw( $url ) ) . '/webfonts' );
	}
}

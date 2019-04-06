<?php
/**
 * Register REST API Routes.
 *
 * @package Gridd
 * @since 1.1
 *
 * phpcs:ignoreFile WordPress.Files.FileName.InvalidClassFileName
 */

namespace Gridd;

/**
 * Implements the custom REST Routes.
 *
 * @since 1.1
 */
class Rest_Routes extends \WP_REST_Controller {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.1
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}


	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @access public
	 * @since 1.1
	 * @return void
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = 'gridd/v' . $version;

		$partials = array_keys( Rest::get_all_partials() );

		foreach ( $partials as $partial ) {
			$base = "partials/$partial";

			register_rest_route(
				$namespace,
				'/' . $base,
				[
					[
						'methods'             => 'GET',
						'callback'            => [ $this, 'get_partial' ],
						'permission_callback' => '__return_true',
						'args'                => [
							'partial' => $partial,
						],
					],
				]
			);
		}
	}

	/**
	 * Get a partial.
	 *
	 * @access public
	 * @since 1.1
	 * @param WP_REST_Request $request Full data about the request.
	 * @return string                  The markup for this grid-part.
	 */
	public function get_partial( $request ) {
		// Get the attributes.
		$attributes = $request->get_attributes();

		// Get the partial.
		if ( isset( $attributes['args'] ) && isset( $attributes['args']['partial'] ) ) {
			$partial = $attributes['args']['partial'];
			if ( $partial ) {
				ob_start();
				do_action( 'gridd_the_partial', $partial );
				return ob_get_clean();		
			}
		}
	}
}

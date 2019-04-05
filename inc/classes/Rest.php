<?php
/**
 * REST API Implementation.
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
class Rest {

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		if ( AMP::is_active() ) {
			return;
		}
		add_action( 'wp_footer', [ $this, 'script' ], PHP_INT_MAX );
	}

	/**
	 * Get an array of partials we want to defer.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @return array
	 */
	public static function get_partials() {
		return get_theme_mod( 'gridd_rest_api_partials', [ 'footer', 'nav-handheld' ] );
	}

	/**
	 * Check if a partial is deferred or not.
	 *
	 * @static
	 * @access public
	 * @since 1.1
	 * @param string $id The partial ID.
	 * @return bool
	 */
	public static function is_partial_deferred( $id ) {
		if ( AMP::is_active() ) {
			return false;
		}
		return ( \in_array( $id, self::get_partials(), true ) );
	}

	/**
	 * Print the script in the footer.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function script() {
		?>
		<script>
			document.addEventListener( 'DOMContentLoaded', function( event ) {
				var event, griddRestParts = <?php echo wp_json_encode( self::get_partials() ); ?>;
				Array.prototype.forEach.call( griddRestParts, function( id, i ) {
					var request = new XMLHttpRequest();
					request.open( 'GET', '<?php echo esc_url_raw( site_url( '?rest_route=/gridd/v1/partials/' ) ); ?>' + id, true );
					request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
					request.onreadystatechange = function() {
						if ( 4 === request.readyState ) {
							event = new CustomEvent( 'griddRestPart', { detail: id } );
							console.log( id );
							document.querySelectorAll( '.gridd-tp-' + id )[0].outerHTML = JSON.parse( request.response );
							console.log( request );
							document.dispatchEvent( event );
						}
					};
					request.send();
				});
			});
		</script>
		<?php
	}
}

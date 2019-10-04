/**
 * Remove squared button style
 *
 * @since 1.0.19
 */
/* global wp */
wp.domReady( function() {
	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
} );

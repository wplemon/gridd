/* global griddComputeEm */
/* jshint -W098 */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	var i = 0;

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		});
	});
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		});
	});

	wp.customize( 'gridd_grid_content_max_width', function( value ) {
		value.bind( function() {
			griddComputeEm();
		});
	});

	_.each( [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ], function( i ) {
		var setting = 'gridd_grid_nav_' + i + '_bg_color',
			cssVar  = '--gridd-nav-' + i + '-submenu-bg';

		wp.customize( setting, function( value ) {
			document.body.style.setProperty( cssVar, jQuery.Color( value.get() ).alpha( 1 ).toHexString( false ) );
			value.bind( function( to ) {
				document.body.style.setProperty( cssVar, jQuery.Color( to ).alpha( 1 ).toHexString( false ) );
			});
		});
	});

	// Compute content-max-width.
	_.each( [ 'gridd_fluid_typography_ratio', 'gridd_grid_content_max_width', 'gridd_body_font_size' ], function( setting ) {
		wp.customize( setting, function( value ) {
			value.bind( function( to ) {
				setTimeout( function() {
					griddComputeEm();
				}, 50 );
			});
		});
	});

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css({
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				});
			} else {
				$( '.site-title, .site-description' ).css({
					'clip': 'auto',
					'position': 'relative'
				});
				$( '.site-title a, .site-description' ).css({
					'color': to
				});
			}
		});
	});

	// Grid control.
	wp.customize( 'gridd_grid', function( value ) {
		value.bind( function( val ) {
			if ( 'string' === typeof val ) {
				val = JSON.parse( val );
			}
		});
	});
} ( jQuery ) );

/**
 * Gets a readable text color.
 *
 * @since 1.0
 * @param {string} bg - The background color (hex).
 * @returns {string} - Text color (hex).
 */
function griddGetContrastColor( bg ) {
	var red, green, blue, yiq;
	bg = bg.replace( '#', '' );
	if ( 3 === bg.length ) {
		bg = bg.substr( 0, 1 ) + bg.substr( 0, 1 ) + bg.substr( 1, 1 ) + bg.substr( 1, 1 ) + bg.substr( 2, 1 ) + bg.substr( 2, 1 );
	}

	red   = parseInt( bg.substr( 0, 2 ), 16 );
	green = parseInt( bg.substr( 2, 2 ), 16 );
	blue  = parseInt( bg.substr( 4, 2 ), 16 );

	// See https://en.wikipedia.org/wiki/YIQ.
	yiq = ( ( red * 299 ) + ( green * 587 ) + ( blue * 114 ) ) / 1000;
	return ( 128 <= yiq ) ? '#000000' : '#ffffff';
}

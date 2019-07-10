/* global griddComputeEm, wcagColors, griddCustomizerVars */
/* jshint -W098 */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

/**
 * Gets a readable text color.
 *
 * @since 1.0
 * @param {string} bg - The background color (hex).
 * @returns {string} - Text color (hex).
 */
function griddGetContrastColor( bg ) {
	var color = wcagColors.getColorProperties( bg );
	return wcagColors.getContrast( color.lum, 1 ) > wcagColors.getContrast( color.lum, 0 ) ? '#ffffff' : '#000000';
}

( function() {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			jQuery( '.site-title a' ).text( to );
		});
	});
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			jQuery( '.site-description' ).text( to );
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
			value.bind( function( to ) { // eslint-disable-line no-unused-vars
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
				jQuery( '.site-title, .site-description' ).css({
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				});
			} else {
				jQuery( '.site-title, .site-description' ).css({
					'clip': 'auto',
					'position': 'relative'
				});
				jQuery( '.site-title, .site-title a, .site-description' ).css({
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

	/**
	 * Automate text-color.
	 *
	 * We're using a proxy hidden control because the plus version
	 * includes a premium control for colorpickers which allows WCAG-compliant colors to be selected by the user.
	 * In the free version of the theme we're automatically picking either white or black
	 * depending on their background-color selection.
	 */
	_.each( griddCustomizerVars.autoText, function( textColor, backgroundColor ) {
		wp.customize( backgroundColor, function( value ) {
			value.bind( function( to ) {
				window.parent.window.wp.customize.control( textColor ).setting.set( griddGetContrastColor( to ) );
			});
		});
	});
} () );

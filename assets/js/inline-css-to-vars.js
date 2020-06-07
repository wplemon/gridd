document.querySelectorAll( '[style]' ).forEach( function( el ) {
	el.attributes.style.value.split( ';' ).forEach( function( rule ) {
		if ( 0 === rule.indexOf( 'background-color:' ) ) {
			el.style.setProperty( '--bg', rule.split( ':' )[ 1 ] );
		}
		if ( 0 === rule.indexOf( 'color:' ) ) {
			el.style.setProperty( '--cl', rule.split( ':' )[ 1 ] );
		}
	});
});

Object.keys( window.griddPalette ).forEach( function( color ) {
	document.querySelectorAll( '.has-' + color + '-color' ).forEach( function( el ) {
		el.style.setProperty( '--cl', window.griddPalette[ color ] );
	});

	document.querySelectorAll( '.has-' + color + '-background-color' ).forEach( function( el ) {
		el.style.setProperty( '--bg', window.griddPalette[ color ] );
	});
});

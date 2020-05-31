document.querySelectorAll( '[style]' ).forEach( function( el ) {
	el.attributes.style.value.split( ';' ).forEach( function( rule ) {
		if ( 0 === rule.indexOf( 'background-color:' ) ) {
			el.style.setProperty( '--bg', rule.split( ':' )[ 1 ] );
		}
		if ( 0 === rule.indexOf( 'color:' ) ) {
			el.style.setProperty( '--cl', rule.split( ':' )[ 1 ] );
		}
	});
	Object.keys( window.griddPalette ).forEach( function( color ) {
		if ( el.classList.contains( 'has-' + color + '-color' ) ) {
			el.style.setProperty( '--cl', window.griddPalette[ color ] );
		}
		if ( el.classList.contains( 'has-' + color + '-background-color' ) ) {
			el.style.setProperty( '--bg', window.griddPalette[ color ] );
		}
	});
});

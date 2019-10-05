function griddSearchSlideUpFocus() {
	var el = document.querySelector( '.gridd-tp-header_search.slide-up .gridd-toggle' );
	el.addEventListener( 'click', function( e ) {
		setTimeout( function() {
			if ( el.classList.contains( 'toggled-on' ) ) {
				document.querySelector( '.header-searchform-wrapper .search-field' ).focus();
			}
		}, 50 );
	} );
}
griddSearchSlideUpFocus();

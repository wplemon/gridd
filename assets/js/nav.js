/**
 * Handle togglers.
 */
Array.prototype.forEach.call( document.querySelectorAll( '.gridd-toggle' ), function( el ) {
	el.addEventListener( 'click', function( e ) {
		e.preventDefault();

		// Toggle the "toggled-on" class.
		el.classList.toggle( 'toggled-on' );

		// Toggle aria-expanded.
		if ( el.classList.contains( 'toggled-on' ) ) {
			el.setAttribute( 'aria-expanded', 'true' );
		} else {
			el.setAttribute( 'aria-expanded', 'false' );
		}
	}, window.griddSupportsPassive ? {
		passive: true
	} : false );
});

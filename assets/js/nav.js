/**
 * Handle togglers.
 */
var griddToggleButtonClick = function( id ) {
	var el = document.querySelector( 'button[data-uid="' + id + '"]' );

    // Toggle the "toggled-on" class.
	el.classList.toggle( 'toggled-on' );

	// Toggle aria-expanded.
	if ( el.classList.contains( 'toggled-on' ) ) {
		el.setAttribute( 'aria-expanded', 'true' );
	} else {
		el.setAttribute( 'aria-expanded', 'false' );
	}
};

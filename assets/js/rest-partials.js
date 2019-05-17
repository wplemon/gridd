/* global griddRestParts, griddRestRoute */
document.addEventListener( 'DOMContentLoaded', function() {
	Array.prototype.forEach.call( griddRestParts, function( id, i ) {
		var request = new XMLHttpRequest(),
			event,
			el;
		request.open( 'GET', griddRestRoute + id, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
		request.onreadystatechange = function() {
			if ( 4 === request.readyState ) {
				el = document.querySelectorAll( '.gridd-tp-' + id )[0];
				if ( el ) {
					event        = new CustomEvent( 'griddRestPart', { detail: id });
					el.outerHTML = JSON.parse( request.response );
					document.dispatchEvent( event );
				}
				if ( i === griddRestParts.length - 1 ) {
					event = new CustomEvent( 'griddRestDone', { detail: id } );
					document.dispatchEvent( event );
				}
			}
		};
		request.send();
	});
});

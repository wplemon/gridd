
/**
 * Handle clicks on the main menu parts.
 *
 * @param {Element} el
 */
function griddMenuItemExpand( el ) { // eslint-disable-line no-unused-vars
	var ul = el.parentNode.parentNode.querySelector( 'ul' ),
		expand = ( 'none' === window.getComputedStyle( ul ).display );
	ul.style.display = expand ? 'block' : 'none';
	if ( expand ) {
		el.classList.add( 'active' );
		ul.setAttribute( 'tabindex', '-1' );
	} else {
		el.classList.remove( 'active' );
	}
}

document.querySelectorAll( '.gridd-navigation ul.sub-menu' ).forEach( function( subMenu ) {
	subMenu.addEventListener( 'blur', function( e ) {
		if ( ! e.relatedTarget.closest( '.sub-menu' ) ) {
			subMenu.style.display = 'none';
			subMenu.parentNode.querySelector( '.gridd-menu-item-wrapper.has-arrow button' ).classList.remove( 'active' );
		}
	}, true );
});


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
		var prev = e.target,
			next = e.relatedTarget,
			prevUl = prev ? prev.closest( '.sub-menu' ) : null,
			nextUl = next ? next.closest( '.sub-menu' ) : null;

		if ( prevUl && prevUl !== nextUl && ( ! nextUl || ! prevUl.contains( nextUl ) ) ) {
			prevUl.style.display = 'none';
			prevUl.parentNode.querySelector( 'button' ).classList.remove( 'active' );
		}
	}, true );
});

function griddNavShouldCollapse() {
	document.querySelectorAll( '.gridd-tp-nav' ).forEach( function( navWrapper ) {
		var nav = navWrapper.querySelector( 'nav' ),
			navLis = nav.querySelector( 'ul' ).children,
			wrapperBounds = navWrapper.getBoundingClientRect(),
			wrapperL = wrapperBounds.left,
			wrapperR = wrapperBounds.right,
			navStartL = navLis[ 0 ].getBoundingClientRect().left,
			navStartR = navLis[ 0 ].getBoundingClientRect().right,
			navEndL = navLis[ navLis.length - 1 ].getBoundingClientRect().left,
			navEndR = navLis[ navLis.length - 1 ].getBoundingClientRect().right,
			shouldCollapse = ( navStartL < wrapperL || navEndL < wrapperL || navStartR > wrapperR || navEndR > wrapperR );

		if ( shouldCollapse ) {
			navWrapper.classList.add( 'should-collapse' );
		} else {
			navWrapper.classList.remove( 'should-collapse' );
		}
	});
}
griddNavShouldCollapse();
window.onresize = griddNavShouldCollapse;

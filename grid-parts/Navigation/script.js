
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
			isHorizontal = nav.classList.contains( 'gridd-nav-horizontal' ) || nav.classList.contains( 'gridd-nav-default-horizontal' ),
			navLis,
			wrapperBounds,
			wrapperL,
			wrapperR,
			navStartL,
			navStartR,
			navEndL,
			navEndR,
			navCollapseWidth,
			navStoredCollapseWidth,
			shouldCollapse;

		if ( navWrapper.classList.contains( 'desktop-icon' ) ) {
			if ( ! navWrapper.classList.contains( 'should-collapse' ) ) {
				navWrapper.classList.add( 'should-collapse' );
			}
			return;
		}
		navLis = nav.querySelector( 'ul' ).children;
		wrapperBounds = navWrapper.getBoundingClientRect();
		wrapperL = wrapperBounds.left;
		wrapperR = wrapperBounds.right;
		navStartL = navLis[ 0 ].getBoundingClientRect().left;
		navStartR = navLis[ 0 ].getBoundingClientRect().right;
		navEndL = navLis[ navLis.length - 1 ].getBoundingClientRect().left;
		navEndR = navLis[ navLis.length - 1 ].getBoundingClientRect().right;
		navCollapseWidth = parseInt( Math.max( navEndR - navStartL, navStartR - navEndL ), 10 );

		navStoredCollapseWidth = nav.getAttribute( 'data-collapse-width' );
		if ( ! navStoredCollapseWidth || navStoredCollapseWidth < navCollapseWidth ) {
			nav.setAttribute( 'data-collapse-width', parseInt( navCollapseWidth, 10 ) );
		}

		shouldCollapse = ( navStartL < wrapperL || navEndL < wrapperL || navStartR > wrapperR || navEndR > wrapperR );

		if ( isHorizontal ) {
			nav.classList.add( 'gridd-nav-default-horizontal' );
		}

		if ( shouldCollapse || wrapperBounds.width < navStoredCollapseWidth ) {
			navWrapper.classList.add( 'should-collapse' );

			if ( isHorizontal ) {
				nav.classList.remove( 'gridd-nav-horizontal' );
				nav.classList.add( 'gridd-nav-vertical' );
			}
		} else {
			navWrapper.classList.remove( 'should-collapse' );

			if ( isHorizontal ) {
				nav.classList.add( 'gridd-nav-horizontal' );
				nav.classList.remove( 'gridd-nav-vertical' );
			}
		}
	});
}

function griddNavShouldCollapseDebounced() {
	if ( ! window.resizeDebouncedTimeout ) {
		window.resizeDebouncedTimeout = setTimeout( function() {
			window.resizeDebouncedTimeout = null;
			griddNavShouldCollapse();
		}, 200 );
	}
}

griddNavShouldCollapse();
window.onresize = griddNavShouldCollapseDebounced;

/**
 * Polyfill for "closest" function in older IE versions.
 *
 * @since 1.1.16
 * @link https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
 */
if ( ! Element.prototype.matches ) {
	Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}
if ( ! Element.prototype.closest ) {
	Element.prototype.closest = function( s ) {
		var el = this;
		do {
			if ( el.matches( s ) ) {
				return el;
			}
			el = el.parentElement || el.parentNode;
		} while ( null !== el && 1 === el.nodeType );
		return null;
	};
}

/**
 * Handle togglers.
 */
function griddToggleButtonClick( id ) { // eslint-disable-line no-unused-vars
	var el = document.querySelector( 'button[data-uid="' + id + '"]' );

	griddNavToggleFocusByEl( el );

    // Toggle the "toggled-on" class.
	el.classList.toggle( 'toggled-on' );

	// Toggle aria-expanded.
	if ( el.classList.contains( 'toggled-on' ) ) {
		el.setAttribute( 'aria-expanded', 'true' );
	} else {
		el.setAttribute( 'aria-expanded', 'false' );
	}
}

/**
 * Handle clicks on the main menu parts.
 *
 * @param {Element} el
 */
function griddMenuItemExpand( el ) {
	var ul = el.parentNode.parentNode.querySelector( 'ul' ),
		expand = ( 'none' === window.getComputedStyle( ul ).display );
	ul.style.display = expand ? 'block' : 'none';
	if ( expand ) {
		el.classList.add( 'active' );
	} else {
		el.classList.remove( 'active' );
	}
}

window.onload = function() {
	var griddNavLinks = document.querySelectorAll( '.menu-item a' ),
		i;
	for ( i = 0; i < griddNavLinks.length; i++ ) {
		griddNavLinks[ i ].addEventListener( 'focus', griddNavToggleFocus, true );
		griddNavLinks[ i ].addEventListener( 'blur', griddNavToggleFocus, true );
	}
};

function griddNavToggleFocus() {
	griddNavToggleFocusByEl( this );
}

function griddNavToggleFocusByEl( el ) {
	var isMenu = el.closest( '.menu' ),
		closestSubMenu,
		closestUl,
		allOpenSubMenuButtons,
		i;

	if ( isMenu ) {
		closestSubMenu        = el.closest( '.sub-menu' );
		closestUl             = closestSubMenu ? closestSubMenu.closest( 'ul' ) : el.closest( 'ul' );
		allOpenSubMenuButtons = closestUl.querySelectorAll( '.menu-item .gridd-toggle.toggled-on' );
		for ( i = 0; i < allOpenSubMenuButtons.length; i++ ) {
			if ( null === closestSubMenu || ( closestSubMenu && closestSubMenu.parentNode.querySelector( '.menu-item .gridd-toggle.toggled-on' ) !== allOpenSubMenuButtons[ i ] ) ) {
				if ( allOpenSubMenuButtons[ i ] !== el ) {
					allOpenSubMenuButtons[ i ].classList.remove( 'toggled-on' );
					allOpenSubMenuButtons[ i ].setAttribute( 'aria-expanded', 'false' );
				}
			}
		}
	}
}

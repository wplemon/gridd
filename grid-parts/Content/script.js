var griddComputeEm = function() {
	var root  = document.querySelectorAll( ':root' )[0],
		style = getComputedStyle( document.body ),
		el    = document.getElementById( 'content-width-calc-helper' ),
		contentWidth,
		maxWidthEm,
		maxWidthCh,
		width;

	contentWidth = style.getPropertyValue( '--cmw' );
	contentWidth = contentWidth && '' !== contentWidth ? contentWidth : '45em';
	maxWidthEm   = -1 === contentWidth.indexOf( 'rem' ) && -1 !== contentWidth.indexOf( 'em' );
	maxWidthCh   = -1 !== contentWidth.indexOf( 'ch' );

	width = contentWidth;
	if ( maxWidthEm || maxWidthCh ) {
		width = parseFloat( getComputedStyle( el, null ).width.replace( 'px', '' ) ) + 'px';
	}

	root.style.setProperty( '--mw-c', width );
};

window.addEventListener( 'resize', function() {
	griddComputeEm();
}, window.griddSupportsPassive ? {
	passive: true
} : false );
griddComputeEm();

/**
 * Announce external links to screen-readers.
 *
 * @since 3.0.0
 */
document.querySelectorAll( 'a[target="_blank"]' ).forEach( function( el ) {
	var screenReaderText = document.createElement( 'span' );
	screenReaderText.classList.add( 'screen-reader-text' );
	screenReaderText.innerHTML = '<?php esc_html_e( "Open Link in new tab", "gridd" ); ?>';
	el.appendChild( screenReaderText );
});

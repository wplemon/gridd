var griddComputeEm = function() {
	var root  = document.querySelectorAll( ':root' )[0],
		style = getComputedStyle( document.body ),
		em    = style.getPropertyValue( 'font-size' ),
		el    = document.getElementById( 'content-width-calc-helper' ),
		contentWidth,
		maxWidthEm,
		maxWidthCh,
		width;

	contentWidth = style.getPropertyValue( '--gridd-content-max-width' );
	contentWidth = contentWidth && '' !== contentWidth ? contentWidth : '45em';
	maxWidthEm   = -1 === contentWidth.indexOf( 'rem' ) && -1 !== contentWidth.indexOf( 'em' );
	maxWidthCh   = -1 !== contentWidth.indexOf( 'ch' );

	width = contentWidth;
	if ( maxWidthEm || maxWidthCh ) {
		width = parseFloat( getComputedStyle( el, null ).width.replace( 'px', '' ) ) + 'px';
	}

	root.style.setProperty( '--gridd-em', em );
	root.style.setProperty( '--gridd-content-max-width-calculated', width );
};

window.addEventListener( 'resize', function() {
	griddComputeEm();
}, window.griddSupportsPassive ? {
	passive: true
} : false );
griddComputeEm();

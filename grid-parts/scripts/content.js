var griddComputeEm = function() {
	var root  = document.querySelectorAll( ':root' )[0],
		style = getComputedStyle( document.body ),
		em    = style.getPropertyValue( 'font-size' ),
		contentWidth,
		maxWidthEm,
		width;

	contentWidth = style.getPropertyValue( '--gridd-content-max-width' );
	contentWidth = contentWidth && '' !== contentWidth ? contentWidth : '45em';
	maxWidthEm   = -1 === contentWidth.indexOf( 'rem' ) && -1 !== contentWidth.indexOf( 'em' );
	width        = maxWidthEm ? parseInt( parseFloat( contentWidth, 10 ) * parseFloat( em ), 10 ) + 'px' : contentWidth;

	root.style.setProperty( '--gridd-em', em );
	root.style.setProperty( '--gridd-content-max-width-calculated', width );
};

window.addEventListener( 'resize', function() {
	griddComputeEm();
}, window.griddSupportsPassive ? {
	passive: true
} : false );
griddComputeEm();

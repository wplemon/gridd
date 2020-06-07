function griddReadProgress() {
	var contentEl = document.getElementById( 'content' ),
		contentHeight = contentEl.offsetHeight,
		contentBoundingBox = contentEl.getBoundingClientRect(),
		progressEl = document.getElementById( 'gridd-progress-indicator' ),
		progressMax = progressEl.getAttribute( 'max' ),
		progress = Math.max( 0, Math.min( progressMax, document.body.offsetHeight - contentBoundingBox.y - contentHeight + window.innerHeight ) );

	if ( progressMax !== contentHeight ) {
		progressEl.setAttribute( 'max', contentHeight );
	}

	progressEl.setAttribute( 'value', progress );
}
function griddReadProgressDebounced() {
	if ( ! window.resizeDebouncedTimeout ) {
		window.resizeDebouncedTimeout = setTimeout( function() {
			window.resizeDebouncedTimeout = null;
			griddReadProgress();
		}, 10 );
	}
}

griddReadProgress();
window.onscroll = griddReadProgressDebounced;

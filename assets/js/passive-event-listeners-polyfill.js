// Test via a getter in the options object to see if the passive property is accessed
var opts;
window.griddSupportsPassive = false;
try {
	opts = Object.defineProperty({}, 'passive', {
		get: function() { // eslint-disable-line getter-return
			window.griddSupportsPassive = true;
		}
	});
	window.addEventListener( 'testPassive', null, opts );
	window.removeEventListener( 'testPassive', null, opts );
} catch ( e ) {}

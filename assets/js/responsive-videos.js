function griddResponsiveFullWidthVideo() {
    var iFrames = document.querySelectorAll( 'iframe' ),

        // Taken from Jetpack, see the 'jetpack_responsive_videos_oembed_videos' filter.
		videoSources = [
			'https?://((m|www)\.)?youtube\.com/watch',
			'https?://((m|www)\.)?youtube\.com/playlist',
			'https?://youtu\.be/',
			'https?://(.+\.)?vimeo\.com/',
			'https?://(www\.)?dailymotion\.com/',
			'https?://dai.ly/',
			'https?://(www\.)?hulu\.com/watch/',
			'https?://wordpress.tv/',
			'https?://(www\.)?funnyordie\.com/videos/',
			'https?://vine.co/v/',
			'https?://(www\.)?collegehumor\.com/video/',
			'https?://(www\.|embed\.)?ted\.com/talks/'
		];

	Array.prototype.forEach.call( iFrames, function( el ) {
		Array.prototype.forEach.call( videoSources, function( source ) {
			var src = el.getAttribute( 'src' ),
				height = el.getAttribute( 'height' ),
				width = el.getAttribute( 'width' ),
				calcHeight;

			if ( src.match( source ) ) {
				if ( width && height ) {
					calcHeight = ( el.offsetWidth / width ) * height;
					el.style.height = calcHeight + 'px';
				}
			}
		});
	});
}

/**
 * Resize videos when needed.
 *
 * @since 1.0
 */
( function() {
	griddResponsiveFullWidthVideo();
	window.addEventListener( 'resize', function() {
		griddResponsiveFullWidthVideo();
	});
}() );

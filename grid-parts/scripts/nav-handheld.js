function griddNavHandheldSearchBlur() {
    var searchField = document.querySelector( '#gridd-handheld-search-wrapper .search-field' );
    if ( searchField ) {
        searchField.addEventListener( 'blur', function() {
            var button = document.querySelector( '#gridd-handheld-search .gridd-nav-handheld-btn' );
            button.classList.remove( 'toggled-on' );
            button.setAttribute( 'aria-expanded', 'false' );
        }, true );
    }
}

griddNavHandheldSearchBlur();

document.addEventListener( 'griddRestDone', function( e ) {
    if ( e.detail && 'nav-handheld' === e.detail ) {
        griddNavHandheldSearchBlur();
    }
});
function griddConvertColumnsToGridLayout() {
	var griddColumns = document.querySelectorAll( '.wp-block-columns' );

	griddColumns.forEach( function( columnsWrapper ) {
		var columns = columnsWrapper.querySelectorAll( '.wp-block-column' ),
			containerGridTemplateCols = [];

		columns.forEach( function( column ) {
			if ( column.style.flexBasis ) {
				containerGridTemplateCols.push( 'minmax(300px, calc(' + column.style.flexBasis + ' - 0.5em))'  );
			} else {
				containerGridTemplateCols.push( '1fr' );
			}
		});

		columnsWrapper.style.display = 'grid';
		columnsWrapper.style.gridTemplateColumns = containerGridTemplateCols.join( ' ' );
		columnsWrapper.style.gridGap = '1em';
	});
}
griddConvertColumnsToGridLayout();

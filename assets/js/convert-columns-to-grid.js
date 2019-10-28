function griddConvertColumnsToGridLayout() {
	var griddColumns = document.querySelectorAll( '.wp-block-columns' );

	griddColumns.forEach( function( columnsWrapper ) {
		var columns = columnsWrapper.childNodes,
			gridTemplateCols = [];

		columns.forEach( function( column ) {
			if ( column.style.flexBasis ) {
				gridTemplateCols.push( 'minmax(300px, calc(' + column.style.flexBasis + ' - 0.5em))'  );
			} else {
				gridTemplateCols.push( 'minmax(300px, 1fr)' );
			}
		});

		columnsWrapper.style.display = 'grid';
		columnsWrapper.style.gridTemplateColumns = gridTemplateCols.join( ' ' );
		columnsWrapper.style.gridGap = '1em';
	});
}
griddConvertColumnsToGridLayout();

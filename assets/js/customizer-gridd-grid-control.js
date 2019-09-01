/* global griddGridControl, DragSelect */
/* jshint -W024 */
wp.customize.controlConstructor.gridd_grid = wp.customize.Control.extend({

	gridVal: {
		rows: 4,
		columns: 3,
		areas: {
			content: {
				cells: [
					[ 1, 1 ],
					[ 1, 2 ],
					[ 2, 1 ],
					[ 2, 2 ]
				]
			}
		},
		gridTemplate: {
			rows: {},
			columns: {}
		}
	},
	cellsNr: 1,
	selectedCells: [],
	editingPart: false,
	dragSelect: {},

	/**
	 * Triggered when the control is ready.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	ready: function() {
		var control = this;

		this.gridVal     = _.defaults( control.setting._value, control.gridVal );
		this.drawGrid    = _.debounce( _.bind( this.drawGridSelector, this ), 50 );
		this.drawPreview = _.debounce( _.bind( this.drawGridSelectedParts, this ), 50 );
		this.save        = _.debounce( _.bind( this.saveValue, this ), 100 );

		// Make sure areas is an object.
		if ( _.isArray( this.gridVal.areas ) ) {
			this.gridVal.areas = {};
		}
		this.sanitizeGridVal();

		this.cellsNr = control.gridVal.rows * control.gridVal.columns;

		control.drawGrid();
		control.drawPreview();
		control.gridButtons();
		control.selectPartButtons();
		control.setPartsFromAllGridControls();
	},

	/**
	 * Add and remove rows and columns.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	gridButtons: function() {
		var control = this;

		// Add column.
		control.container.find( '.button.add-column' ).on( 'click', function( e ) {
			control.addColumn();
			e.preventDefault();
		});

		// Remove Column.
		control.container.find( '.button.remove-column' ).on( 'click', function( e ) {
			control.removeColumn();
			e.preventDefault();
		});

		// Add row.
		control.container.find( '.button.add-row' ).on( 'click', function( e ) {
			control.addRow();
			e.preventDefault();
		});

		// Remove Row.
		control.container.find( '.button.remove-row' ).on( 'click', function( e ) {
			control.removeRow();
			e.preventDefault();
		});

		// Toggle zoom.
		control.container.find( '.gridd-grid-zoom-in' ).on( 'click', function( e ) {
			control.container.toggleClass( 'fixed-position' );
			e.preventDefault();
		});
	},

	/**
	 * Gets the nr of columns and rows from active parts.
	 *
	 * @since 1.0
	 * @returns {Object}
	 */
	getMaxColsRowsFromActiveParts: function() {
		var control = this,
			maxCols = 1,
			maxRows = 1;

		_.each( control.gridVal.areas, function( areaArgs ) {
			_.each( areaArgs.cells, function( cell ) {
				if ( cell[0] > maxRows ) {
					maxRows = cell[0];
				}
				if ( cell[1] > maxCols ) {
					maxCols = cell[1];
				}
			});
		});
		return [ maxRows, maxCols ];
	},

	/**
	 * Adds a column.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	addColumn: function() {
		var control = this;
		control.gridVal.columns++;
		control.drawGrid();
		control.drawPreview();
		control.save();
	},

	/**
	 * Removes a column.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	removeColumn: function() {
		var control  = this,
			maxParts = control.getMaxColsRowsFromActiveParts(),
			newVal   = control.gridVal.columns - 1;

		if ( newVal >= maxParts[1] ) {
			control.gridVal.columns--;
			control.drawGrid();
			control.drawPreview();
			control.save();
		}
	},

	/**
	 * Adds a row.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	addRow: function() {
		var control = this;
		control.gridVal.rows++;
		control.drawGrid();
		control.drawPreview();
		control.save();
	},

	/**
	 * Removes a row.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	removeRow: function() {
		var control  = this,
			maxParts = control.getMaxColsRowsFromActiveParts(),
			newVal   = control.gridVal.rows - 1;

		if ( newVal >= maxParts[0] ) {
			control.gridVal.rows--;
			control.drawGrid();
			control.drawPreview();
			control.save();
		}
	},

	/**
	 * Draws the grid selector part.
	 * This contains the dragSelect functionality.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	drawGridSelector: function() {
		var control           = this,
			selectorContainer = control.container.find( '.gridd-grid-selectable' ),
			row,
			column;

		// Empty the grid-selector.
		selectorContainer.empty();

		this.sanitizeGridVal();

		// Add cells to the grid-selector.
		for ( row = 1; row <= control.gridVal.rows; row++ ) {
			for ( column = 1; column <= control.gridVal.columns; column++ ) {

				// Add cells as buttons for better accessibility.
				// See https://github.com/ThibaultJanBeyer/DragSelect for details.
				// TODO: text should be translatable in here.
				selectorContainer.append( '<button class="gridd-grid-selectable-cell" data-row="' + row + '" data-column="' + column + '"><span class="screen-reader-text">Row ' + row + ', Column ' + column + '</span></button>' );
			}
		}

		// Init the DragSelect functionality.
		control.dragSelect = new DragSelect({

			// The cells.
			selectables: document.getElementById( 'customize-control-' + control.id ).getElementsByClassName( 'gridd-grid-selectable-cell' ),

			// Wrapper.
			area: document.getElementById( 'gridd-grid-selectable-' + control.id  ),

			// Callback when the part-selection is finished.
			callback: function( elements ) {

				control.selectedCells = [];

				// Set control.selectedCells.
				_.each( elements, function( element ) {

					// Make sure selected cell has row & column defined.
					if ( control.container.find( element ).data( 'row' ) && control.container.find( element ).data( 'column' ) ) {

						// Add cell to the value.
						control.selectedCells.push( [
							control.container.find( element ).data( 'row' ),
							control.container.find( element ).data( 'column' )
						] );
					}
				});

				// Reveal the part-selection overlay.
				if ( control.selectedCells.length ) {
					if ( ! control.editingPart ) {
						control.drawGridSelectedParts();
						control.container.find( '.gridd-grid-part-selector' ).css( 'display', 'block' );
					} else {
						control.gridVal.areas[ control.editingPart ].cells = control.selectedCells;
						control.setPartsFromAllGridControls();
						control.save();
						control.drawPreview();
					}
				}

				// Exit edit mode.
				// We're adding a 250ms delay to be sure the settings gets updated.
				// 250 is overkill, but better safe than sorry.
				setTimeout( function() {
					control.container.find( '.edit-part-options-done' ).click();
				}, 250 );
			}
		});

		// Prevent page-refresh when a cell is clicked.
		control.container.find( '.gridd-grid-selectable-cell' ).on( 'click', function( e ) {
			e.preventDefault();
		});

		// Style grid to mirror columns & rows layout.
		selectorContainer.css( 'grid-template-columns', 'repeat(' + control.gridVal.columns + ', 1fr)' );
		selectorContainer.css( 'grid-template-rows', 'repeat(' + control.gridVal.rows + ', 65px)' );

		// Since things here have position:absolute, we have to manually set the height of the container.
		control.container.find( '.gridd-grid-builder-grids' ).height( selectorContainer.height() + 30 );

		this.sanitizeGridVal();

		// Draw columns & rows inputs.
		control.drawColumnFields();
		control.drawRowFields();
	},

	/**
	 * Draw the column input fields.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	drawColumnFields: function() {
		var control   = this,
			container = control.container.find( '.gridd-grid-builder-columns' ),
			i;

		container.html( '' );
		for ( i = 0; i < control.gridVal.columns; i++ ) {
			if ( _.isUndefined( control.gridVal.gridTemplate.columns[ i ] ) ) {
				control.gridVal.gridTemplate.columns[ i ] = 'auto';
				control.save();
			}
			container.append( '<input type="text" data-column-css="' + i + '" value="' + control.gridVal.gridTemplate.columns[ i ] + '">' );
		}
		container.css( 'grid-template-columns', 'repeat(' + control.gridVal.columns + ', 1fr)' );

		this.sanitizeGridVal();
		control.container.find( '[data-column-css]' ).on( 'change keyup paste', function() {
			control.gridVal.gridTemplate.columns[ jQuery( this ).data( 'column-css' ) ] = jQuery( this ).val();
			control.save();
		});
		this.sanitizeGridVal();
	},

	/**
	 * Draw the row input fields.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	drawRowFields: function() {
		var control   = this,
			container = control.container.find( '.gridd-grid-builder-rows' ),
			i;

		this.sanitizeGridVal();
		container.html( '' );
		for ( i = 0; i < control.gridVal.rows; i++ ) {
			if ( _.isUndefined( control.gridVal.gridTemplate.rows[ i ] ) ) {
				control.gridVal.gridTemplate.rows[ i ] = 'auto';
				control.save();
			}
			container.append( '<input type="text" data-row-css="' + i + '" value="' + control.gridVal.gridTemplate.rows[ i ] + '">' );
		}
		container.css( 'grid-template-rows', 'repeat(' + control.gridVal.rows + ', 1fr)' );

		control.container.find( '[data-row-css]' ).on( 'change keyup paste', function() {
			control.gridVal.gridTemplate.rows[ jQuery( this ).data( 'row-css' ) ] = jQuery( this ).val();
			control.save();
		});
		this.sanitizeGridVal();
	},

	/**
	 * Builds the grid selected part.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	drawGridSelectedParts: function() {
		var control           = this,
			selectedContainer = control.container.find( '.gridd-grid-selected' ),
			allActiveParts    = control.getPartsFromAllGridControls();

		selectedContainer.empty();
		control.setPartsFromAllGridControls();

		control.container.find( '.gridd-grid-part-selector .grid-part-select option' ).removeAttr( 'disabled' );

		control.container.find( '.gridd-grid-part-selector .grid-part-select option[value=""]' ).attr( 'selected', true );
		_.each( _.keys( allActiveParts ), function( val ) {
			if ( control.params.choices.duplicate ) {
				if ( window.griddGridPartsSelectedAreas[ control.params.choices.duplicate ] && _.contains( window.griddGridPartsSelectedAreas[ control.params.choices.duplicate ], val ) ) {
					return;
				}
			}
			control.container.find( '.gridd-grid-part-selector .grid-part-select option[value="' + val + '"]' ).attr( 'disabled', true );
		});

		// Loops existing parts.
		this.sanitizeGridVal();
		_.each( control.gridVal.areas, function( args, id ) {
			var minRow, maxRow, minCol, maxCol,
				rows     = [],
				cols     = [],
				partHTML = '';

			control.container.find( '.gridd-grid-part-selector .grid-part-select option[value="' + id + '"]' ).attr( 'disabled', true );

			// No reason to do anything for this part if there are no cells defined.
			if ( ! args.cells || _.isEmpty( args.cells ) ) {
				return;
			}

			// Find the minimum & maximum column & row that this part should have.
			_.each( args.cells, function( cell ) {
				rows.push( cell[0] );
				cols.push( cell[1] );
				minRow = _.min( rows );
				maxRow = _.max( rows ) + 1;
				minCol = _.min( cols );
				maxCol = _.max( cols ) + 1;
			});

			partHTML  = '<div class="grid-selected-part selected-part-' + id + '" style="grid-row-start:' + minRow + ';grid-row-end:' + maxRow + ';grid-column-start:' + minCol + ';grid-column-end:' + maxCol + ';">';
			partHTML += '<span class="inner" style="background-color:' + control.getPartAttr( id, 'color' )[0] + ';color:' + control.getPartAttr( id, 'color' )[1] + ';">';
			partHTML += '<span class="part-label-wrapper">' + control.getPartAttr( id, 'label' ) + '</span>';
			partHTML += control.getActionsHTML( id );
			partHTML += '</span>';
			partHTML += '</div>';

			// Add the part.
			selectedContainer.append( partHTML );
		});

		if ( control.editingPart ) {

			control.container.find( '.gridd-grid-builder-grids' ).addClass( 'editing-parts' );
			control.container.find( '.gridd-grid-builder-grids-wrapper' ).addClass( 'editing-parts' );

			// Add .editing class to the preview in the grid.
			control.container.find( '.grid-selected-part.selected-part-' + control.editingPart ).addClass( 'editing' );
		} else {
			control.container.find( '.gridd-grid-builder-grids' ).removeClass( 'editing-parts' );
			control.container.find( '.gridd-grid-builder-grids-wrapper' ).removeClass( 'editing-parts' );
		}

		selectedContainer.css( 'grid-template-columns', 'repeat(' + control.gridVal.columns + ', 1fr)' );
		selectedContainer.css( 'grid-template-rows', 'repeat(' + control.gridVal.rows + ', 65px)' );

		control.trggerPartEditButton();
		control.triggerPartDeleteButton();
		control.triggerFocusButtons();
	},

	/**
	 * Handles triggering the focus buttons.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	triggerFocusButtons: function() {
		var control = this;

		// Focus on section.
		control.container.find( '.button-gridd-focus' ).on( 'click', function( e ) {
			var part      = jQuery( this ).data( 'part' ),
				sectionID = 'gridd_grid_part_details_' + part;

			wp.customize.section( sectionID ).focus();
			e.preventDefault();
		});
	},

	/**
	 * Gets the actions HTML.
	 *
	 * @since 1.0
	 * @param {string} id - The grid-part ID.
	 * @returns {string}
	 */
	getActionsHTML: function( id ) {
		var control      = this,
			moveSVGIcon  = '<svg style="width:15px;height:15px;" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2"><path d="M237.113 447.502h6.55V512c-10.939 0-22.015-.096-32.111-3.938-22.511-8.565-38.703-31.568-38.935-56.08l-.001-10.013h64.497v5.533zM372.658 512H308.16v-64.498h64.498V512zM512 451.648c-.1 30.959-26.55 60.025-60.018 60.351l-14.827.001v-64.498h10.347v-1.736H512v5.882zm0-70.379h-64.498v-64.498H512v64.498zm-339.384-41.536c-37.475.004-74.951-.055-112.425-.177C29.092 339.262.486 312.519.174 279.538c-.232-73.115-.232-146.231 0-219.346C.467 29.17 27.21.488 60.191.174c73.115-.232 146.232-.232 219.348 0 31.076.296 59.7 27.03 60.017 60.017.116 37.474.173 74.95.169 112.425h27.104v64.497h-27.167c-.027 14.142-.062 28.284-.106 42.426-.299 31.151-27.101 59.706-60.017 60.017-14.142.046-28.284.083-42.426.111v37.804h-64.497v-37.738zM275.06 64.67H64.67v210.39h210.39V64.67zM512 252.274h-64.498v-15.161h-16.176v-64.497c6.886 0 13.771-.02 20.656.001 31.122.299 59.706 27.163 60.017 60.016l.001 19.641z"/></svg>',
			resizeButton = '<button class="resize" data-part="' + id + '" title="' + griddGridControl.l10n.resize + '">' + moveSVGIcon + '<span class="screen-reader-text">' + griddGridControl.l10n.resize + '</span></button>',
			editButton   = '<button class="edit button-gridd-focus" data-part="' + id + '" title="' + griddGridControl.l10n.edit + '"><span class="dashicons dashicons-admin-generic"></span><span class="screen-reader-text">' + griddGridControl.l10n.edit + '</span></button>',
			deleteButton = '<button class="delete" data-part="' + id + '" title="' + griddGridControl.l10n.delete + '"><span class="trash dashicons dashicons-trash"></span><span class="screen-reader-text">' + griddGridControl.l10n.delete + '</span></button>',
			html;

		// Don't allow deleting the content, header & footer.
		if ( 'content' === id || 'header' === id || 'footer' === id ) {
			deleteButton = '';
		}

		html  = '<div class="actions">';

		if ( ! control.params.choices.disablePartButtons || -1 === control.params.choices.disablePartButtons.indexOf( 'edit' ) ) {
			html += editButton;
		}
		if ( ! control.params.choices.disablePartButtons || -1 === control.params.choices.disablePartButtons.indexOf( 'resize' ) ) {
			html += resizeButton;
		}
		if ( ! control.params.choices.disablePartButtons || -1 === control.params.choices.disablePartButtons.indexOf( 'delete' ) ) {
			html += deleteButton;
		}
		html += '</div>';

		return html;
	},

	/**
	 * Actions to run when we select a grid-part.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	selectPartButtons: function() {
		var control = this;

		// When we click on a grid-part, assign the selected cells to this part.
		control.container.find( '.gridd-grid-part-selector .grid-part-select' ).on( 'change', function( e ) {
			var part;

			e.preventDefault();

			control.sanitizeGridVal();
			if ( control.selectedCells ) {
				part = part = jQuery( this ).val();
				control.gridVal.areas[ part ]       = control.gridVal.areas[ part ] || {};
				control.gridVal.areas[ part ].cells = control.selectedCells;
				control.save();
			}
			control.sanitizeGridVal();
			control.setPartsFromAllGridControls();

			// Redraw things.
			control.drawPreview();

			// Clear the selection.
			control.dragSelect.clearSelection();

			// Hide the grid-part selector.
			control.container.find( '.gridd-grid-part-selector' ).css( 'display', 'none' );
		});

		// When we click on the cancel button hide the grid-part selector and clear the selection.
		control.container.find( '.gridd-grid-part-selector-cancel' ).on( 'click', function( e ) {
			control.container.find( '.gridd-grid-part-selector' ).css( 'display', 'none' );
			control.dragSelect.clearSelection();
			e.preventDefault();
		});
	},

	/**
	 * Triggers for edit buttons.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	trggerPartEditButton: function() {
		var control  = this,
			allCells = document.getElementsByClassName( 'gridd-grid-selectable-cell' );

		control.container.find( '.grid-selected-part .actions .resize' ).on( 'click', function( e ) {

			var selectables = [],

				// Get the part-ID.
				id = jQuery( this ).data( 'part' );

			control.editingPart = id;

			// Do not refresh the page when clicking the button.
			e.preventDefault();

			// Add .editing class to the preview in the grid.
			control.container.find( '.grid-selected-part' ).addClass( 'editing' );

			// Select parts in dragSelect.
			selectables = [];
			control.sanitizeGridVal();
			if ( control.gridVal.areas[ id ] && control.gridVal.areas[ id ].cells ) {
				_.each( control.gridVal.areas[ id ].cells, function( cell ) {
					_.each( allCells, function( HTMLcell ) {
						if ( cell[0] === parseInt( HTMLcell.dataset.row, 10 ) && cell[1] === parseInt( HTMLcell.dataset.column, 10 ) ) {
							selectables.push( HTMLcell );
						}
					});
				});
			}
			control.sanitizeGridVal();

			// Redraw things.
			control.drawPreview();

			// Set selected parts.
			control.dragSelect.setSelection( selectables );
		});

		control.container.find( '.edit-part-options-done' ).on( 'click', function( e ) {

			// Unset the editingPart var.
			control.editingPart = '';

			// Do not refresh the page.
			e.preventDefault();

			// Add .editing class to the preview in the grid.
			control.container.find( '.grid-selected-part' ).removeClass( 'editing' );
			control.container.find( '.gridd-grid-builder-grids' ).removeClass( 'editing-parts' );
			control.container.find( '.gridd-grid-builder-grids-wrapper' ).removeClass( 'editing-parts' );

			// Toggle class to hide the advanced options.
			control.dragSelect.clearSelection();
		});
	},

	/**
	 * Triggers for delete button.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	triggerPartDeleteButton: function() {
		var control  = this;

		control.container.find( '.grid-selected-part .actions .delete' ).on( 'click', function( e ) {

			// Get the part-ID.
			var id = jQuery( this ).data( 'part' );

			// Do not refresh the page when clicking the button.
			e.preventDefault();

			// Delete value for this grid-part.
			delete control.gridVal.areas[ id ];
			control.sanitizeGridVal();
			control.setPartsFromAllGridControls();

			// Save.
			control.save();

			// Redraw things.
			control.drawPreview();

			// Exit edit mode.
			// We're adding a 250ms delay to be sure the settings gets updated.
			// 250 is overkill, but better safe than sorry.
			setTimeout( function() {
				control.container.find( '.edit-part-options-done' ).click();
			}, 250 );
		});
	},

	/**
	 * Get a part attribute.
	 *
	 * @since 1.0
	 * @param {string} id - The part-ID.
	 * @param {string} attr - The attribute we're getting.
	 * @returns {string|int}
	 */
	getPartAttr: function( id, attr ) {
		var control = this,
			val     = '';
		_.each( control.params.choices.parts, function( part ) {
			if ( part[ attr ] && id === part.id ) {
				val = part[ attr ];
			}
		});
		return val;
	},

	/**
	 * Sets window.griddGridPartsSelectedAreas.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	setPartsFromAllGridControls: function() {
		var control = this;
		if ( ! window.griddGridPartsSelectedAreas ) {
			window.griddGridPartsSelectedAreas = {};
		}
		window.griddGridPartsSelectedAreas[ control.id ] = _.keys( control.gridVal.areas );
	},

	/**
	 * Gets all active parts from all grid controls.
	 *
	 * @since 1.0
	 * @returns {Object}
	 */
	getPartsFromAllGridControls: function() {
		var control  = this,
			mainGrid = 'gridd_grid',
			allParts = {};

		if ( ! window.griddGridPartsSelectedAreas ) {
			window.griddGridPartsSelectedAreas = {};
		}
		_.each( window.griddGridPartsSelectedAreas[ mainGrid ], function( val ) {
			var subParts = control.getSubPartsFromGridControl( val );

			allParts[ val ] = true;
			if ( subParts ) {
				allParts = jQuery.extend({}, allParts, subParts );
			}
		});
		return allParts;
	},

	getSubPartsFromGridControl: function( part ) {
		var control     = this,
			nestedParts = griddGridControl.nestedParts,
			nested      = nestedParts[ part ],
			subs        = window.griddGridPartsSelectedAreas[ part ],
			allParts    = {};

		if ( nested && window.griddGridPartsSelectedAreas[ nested ] ) {
			return control.getSubPartsFromGridControl( nested );
		}

		if ( subs ) {
			if ( _.isArray( subs ) ) {
				_.each( subs, function( val ) {
					allParts[ val ] = true;
				});
				return allParts;
			}
		}
		return false;
	},

	sanitizeGridVal: function() {
		var control       = this,
			existingParts = [];

		_.each( control.params.choices.parts, function( part ) {
			existingParts.push( part.id );
		});

		// Rows.
		control.gridVal.rows = parseInt( control.gridVal.rows, 10 );

		// Columns.
		control.gridVal.columns = parseInt( control.gridVal.columns, 10 );

		// Areas.
		_.each( control.gridVal.areas, function( partCells, part ) {
			var cellsSanitized = [];
			_.each( partCells.cells, function( cell ) {
				cellsSanitized.push( [ parseInt( cell[0], 10 ), parseInt( cell[1], 10 ) ] );
			});
			if ( -1 < existingParts.indexOf( part ) ) {
				control.gridVal.areas[ part ].cells = cellsSanitized;
			} else {
				delete control.gridVal.areas[ part ];
			}
		});
	},

	/**
	 * Saves the value.
	 *
	 * @since 1.0
	 * @param {string|Object} newValue - The value we're saving.
	 * @returns {void}
	 */
	saveValue: function() {
		var control = this,
			value;

		control.sanitizeGridVal();
		value = jQuery.extend({}, control.gridVal );

		// Upodate window.griddGridPartsSelectedAreas.
		control.setPartsFromAllGridControls();

		// Update value in the hidden input & trigger the change event.
		control.container.find( '.gridd-grid-hidden-value' ).attr( 'value', JSON.stringify( value ) ).trigger( 'change' );
	}
});

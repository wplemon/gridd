/* global griddTemplatePreviewScript */

/**
 * Scripts within the customizer controls window.
 */
( function() {

	var griddSetGridVal = function( setting, value ) {
		var control = wp.customize.control( setting );
		if ( 'gridd_grid' === control.params.type ) {

			// Put source value to the target setting.
			control.gridVal = jQuery.extend({}, value );

			// Redraw and save new value.
			control.drawGrid();
			control.drawPreview();
			control.save();
		}
	};

	wp.customize.bind( 'ready', function() {

		// Focus buttons.
		jQuery( '.button-gridd-focus.global-focus' ).click( function( e ) {
			wp.customize[ jQuery( this ).data( 'context' ) ]( jQuery( this ).data( 'focus' ) ).focus();
			e.preventDefault();
		});

		// Copy-grid-settings buttons.
		jQuery( '.button-gridd-copy-grid-setting' ).click( function( e ) {
			griddSetGridVal( jQuery( this ).data( 'to' ), wp.customize.control( jQuery( this ).data( 'from' ) ).gridVal );

			e.preventDefault();
		});

		// Grid Presets.
		_.each( [
			'gridd_global_grid_preset'
		], function( setting ) {
			var control = wp.customize.control( setting );

			wp.customize( setting, function( value ) {
				value.bind( function( to ) {
					_.each( control.params.preset, function( preset, valueToListen ) {
						if ( valueToListen === to ) {
							_.each( preset.settings, function( controlValue, controlID ) {
								griddSetGridVal( controlID, controlValue );
							});
						}
					});
				});
			});
		});

		// Back buttons in nested grids.
		_.each( window.griddGridPartsSelectedAreas, function( parts, grid ) {
			var nestedParts = {};

			_.each( griddTemplatePreviewScript.nestedGrids, function( v, k ) {
				nestedParts[ k ] = v;
			});

			// Check and make sure we're not in the main grid.
			if ( 'gridd_grid' !== grid ) {

				// Loop parts in the sub-grid.
				_.each( parts, function( part ) {
					var section = jQuery( '#sub-accordion-section-' + part ),
						backBtn = section.find( '.customize-section-back' );

					// Change the behavior of the back button.
					jQuery( backBtn ).click( function( e ) {
						if ( 'header_grid' === grid ) {
							wp.customize.section( 'header' ).focus();
							e.preventDefault();
						} else if ( 'footer_grid' === grid ) {
							wp.customize.section( 'footer' ).focus();
							e.preventDefault();
						} else if ( nestedParts[ grid ] && wp.customize.section( nestedParts[ grid ] ) ) {
							wp.customize.section( nestedParts[ grid ] ).focus();
							e.preventDefault();
						} else if ( griddTemplatePreviewScript.nestedGrids[ grid ] && wp.customize.section( griddTemplatePreviewScript.nestedGrids[ grid ] ) ) {
							wp.customize.section( griddTemplatePreviewScript.nestedGrids[ grid ] ).focus();
							e.preventDefault();
						}
					});
				});
			}
		});

		// Handle clicking on the section-description buttons.
		jQuery( 'button.gridd-section-description-trigger' ).on( 'click', function( e ) {
			var context = jQuery( this ).data( 'context' ),
				popup   = jQuery( this ).parent().find( '.gridd-section-description[data-context="' + context + '"]' );

			if ( 'true' === popup.attr( 'aria-expanded' ) || true === popup.attr( 'aria-expanded' ) ) {
				popup.attr( 'aria-expanded', false );
			} else {
				popup.attr( 'aria-expanded', true );
			}
			e.preventDefault();
		});

		/**
		 * We're hardcoding this because WordPress-Core in its infinite wisdom
		 * doesn't allow filtering the description or anything else for the header-background-image control.
		 *
		 * @since 1.1.12
		 */
		jQuery( '#customize-control-header_image .customize-control-description' ).html( griddTemplatePreviewScript.l10n.headerImageDescription );

		/**
		 * Handle switching target color-a11y mode.
		 *
		 * @since 2.0.0
		 */
		wp.customize( 'target_color_compliance', function( value ) {
			value.bind( function( to ) {
				wp.customize.control.each( function( control ) {
					if ( 'kirki-wcag-link-color' === control.params.type || 'kirki-wcag-lc' === control.params.type ) {
						control.params.choices.forceCompliance = to;
						if ( 'function' === typeof control.getMode && ( 'auto' === control.getMode() || 'recommended' === control.getMode() ) ) {
							control.setting.set( control.getAutoColor( control.setting.get(), true ) );
						}
					}
				});
			});
		});

		/**
		 * Handle palette changes.
		 *
		 * @since 2.0.0
		 */
		wp.customize( 'custom_color_palette', function( value ) {
			value.bind( function( to ) {
				var colors = [];
				if ( 'string' === typeof to ) {
					to = JSON.parse( to.replace( /&#39/g, '"' ) );
				}
				to.forEach( function( item ) {
					colors.push( item.color );
				});

				wp.customize.control.each( function( control ) {
					if ( 'kirki-react-color' === control.params.type ) {
						if ( 'TwitterPicker' === control.params.choices.formComponent ) {
							control.params.choices.colors = colors;
							control.renderContent();
						}
					}
				});
			});
		});

		// Move widget-area settings.
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-footer_sidebar_1', 'footer_sidebar_1' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-footer_sidebar_2', 'footer_sidebar_2' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-footer_sidebar_3', 'footer_sidebar_3' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-footer_sidebar_4', 'footer_sidebar_4' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-1', 'sidebar_1' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-2', 'sidebar_2' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-3', 'sidebar_3' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-4', 'sidebar_4' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-5', 'sidebar_5' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-sidebar-6', 'sidebar_6' );
		griddMoveSectionControlsOnDemand( 'sidebar-widgets-offcanvas-sidebar', 'gridd_plus_offcanvas_sidebar' );
	});

	/**
	 * Move controls from a section to another.
	 *
	 * @since 2.0.0
	 */
	function griddMoveSectionControlsOnDemand( newSectionID, oldSectionID ) {
		var newSection = wp.customize.section( newSectionID ),
			oldSection = wp.customize.section( oldSectionID );

		if ( ! newSection || ! oldSection ) {
			return;
		}

		window.alreadyProcessedSectionMoves = window.alreadyProcessedSectionMoves || {};
		newSection.expanded.bind( function() {
			if ( ! window.alreadyProcessedSectionMoves[ oldSection.id ] ) {
				oldSection.activate( true );
				oldSection.expand();
				oldSection.controls().forEach( function( control ) {
					control.section( newSection.id );
				});
				oldSection.activate( false );
				newSection.expand();
			}
			window.alreadyProcessedSectionMoves[ oldSection.id ] = true;
		});
		oldSection.expanded.bind( function() {
			newSection.expand();
		});
	}

	/**
	 * Link link-color colorpickers hues.
	 *
	 * @since 2.0.0
	 */
	wp.customize( 'links_color', function( value ) {
		value.bind( function( to ) { // eslint-disable-line no-unused-vars
			var mainLinksHue = wp.customize.control( 'links_color' ).getHue();

			wp.customize.control.each( function( control ) {
				if ( wp.hooks.applyFilters( 'griddLinksColorSkipGlobalHueChange', false, control ) ) {
					return;
				}

				if ( ( 'links_color' !== control.id ) && ( 'kirki-wcag-link-color' === control.params.type || 'kirki-wcag-lc' === control.params.type ) ) {
					control.setHue( mainLinksHue );
					control.setting.set( control.getAutoColor( 'hsl(' + mainLinksHue + ',50%,50%)', true ) );
				}
			});
		});
	});
}() );

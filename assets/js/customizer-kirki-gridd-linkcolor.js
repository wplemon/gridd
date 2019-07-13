/**
 * This is a stripped-down version of the premium control "Kirki WCAG Links Color" control.
 * For more details see https://wplemon.com/downloads/kirki-wcag-link-colorpicker/
 * The premium controls allows more flexibility and gives users the ability to choose
 * from an array of accessible and WCAG-compliant colors for their links.
 *
 * @since 1.0
 * @copyright 2019 Aristeidis Stathopoulos, wplemon.
 * @license GPL2.0
 */
/* global wcagColors */
wp.customize.controlConstructor['gridd-wcag-lc'] = wp.customize.Control.extend({

	/**
	 * The selected hue.
	 *
	 * @since 1.0
	 * @var {int}
	 */
	hue: 0,

	/**
	 * An array of all colors for this hue.
	 *
	 * @since 1.0
	 * @var {Array}
	 */
	allColors: false,

	/**
	 * An object containing the accessible colors for AAA, AA & A compliance.
	 *
	 * @since 1.0
	 * @var {Object}
	 */
	colors: {},

	/**
	 * Triggered when the control is ready.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	ready: function() {

		// Set initial hue.
		this.setHue();

		// Get available colors. This is the initial run when the control gets init,
		// no reason to run the debounced method here.
		this.updateColors( false );

		this.initAuto();
	},

	/**
	 * Embed the control.
	 *
	 * Overrides the embed() method to do nothing,
	 * so that the control isn't embedded on load,
	 * unless the containing section is already expanded.
	 *
	 * @since 1.1.0
	 * @returns {void}
	 */
	embed: function() {
		var control   = this,
			sectionId = control.section();

		if ( ! sectionId ) {
			return;
		}
		wp.customize.section( sectionId, function( section ) {
			section.expanded.bind( function( expanded ) {
				if ( expanded ) {
					control.actuallyEmbed();
				}
			});
		});
	},

	/**
	 * Deferred embedding of control.
	 *
	 * This function is called in Section.onChangeExpanded() so the control
	 * will only get embedded when the Section is first expanded.
	 *
	 * @since 1.1.0
	 * @return {void}
	 */
	actuallyEmbed: function() {
		if ( 'resolved' === this.deferred.embedded.state() ) {
			return;
		}
		this.renderContent();
		this.deferred.embedded.resolve();
	},

	/**
	 * Init functionality for auto.
	 *
	 * @since 1.0
	 * @returns {Object} - this
	 */
	initAuto: function() {
		var control = this;

		// Set the hue.
		control.setHue();

		// Update Colors.
		this.updateColors( false );

		// Show the Auto container.
		this.container.find( '.kirki-input-container.auto' ).show();

		// Add the input.
		this.container.find( '.color-hue-container' ).html( '<input class="color-hue" type="text" data-type="hue" value="' + this.hue + '" />' );

		// Init the colorpicker.
		this.initHuePicker();

		this.settingsWatchers = function() {

			// Watch for changes to the background color.
			control.watchSetting( control.params.choices.backgroundColor );

			// Watch for changes to the text color.
			control.watchSetting( control.params.choices.textColor );
		};

		this.settingsWatchers();

		// Expand color-selectors when requested.
		control.container.find( '.expand-selectors' ).on( 'click', function( e ) {
			e.preventDefault();
			control.container.find( '.rating-containers-wrapper' ).toggleClass( 'hidden' );
		});
	},

	/**
	 * Initialize the hue colorpicker.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	initHuePicker: function() {
		var control   = this,
			huePicker = control.container.find( '.color-hue' );

		huePicker.attr( 'value', this.hue );
		huePicker.wpColorPicker({
			change: function() {

				// Small hack: the picker needs a small delay
				setTimeout( function() {

					// Set the hue.
					control.hue = parseInt( huePicker.val(), 10 );

					// Update colors.
					// This gets triggered with a small delay so there's no reason to run the debounced version.
					control.updateColors( true );
				}, 20 );
			}
		});
	},

	/**
	 * Set the hue in the control object as an integer.
	 * If no hue is defined it gets the saved value
	 *
	 * @param {int} hue
	 */
	setHue: function() {
		var color = this.setting.get();
		this.hue  = ( color ) ? wcagColors.getColorProperties( color ).h : 210;
	},

	/**
	 * Gets accessible colors accoring to their rating.
	 *
	 * @param {string} rating - Can be AAA, AA or A.
	 * @returns {Array}
	 */
	queryColors: function( rating ) {
		var backgroundMinContrast,
			surroundingTextMinContrast,
			backgroundColor      = wp.customize( this.params.choices.backgroundColor ).get(),
			backgroundColorProps = wcagColors.getColorProperties( backgroundColor );

		switch ( rating ) {
			case 'AAA':
				backgroundMinContrast      = 7;
				surroundingTextMinContrast = 3;
				break;
			case 'AA':
				backgroundMinContrast      = 4.5;
				surroundingTextMinContrast = 2;
				break;
			case 'A':
				backgroundMinContrast      = 3;
				surroundingTextMinContrast = 1;
				break;
		}

		if ( ! this.allColors ) {
			this.allColors = wcagColors.getAll({
				hue: this.hue,
				minHueDiff: 0,
				maxHueDiff: 3,
				stepDiff: 3,
				stepSaturation: 0.025,
				stepLightness: 0.025,
				minLightness: 0.5 < backgroundColorProps.l ? 0 : 0.5,
				maxLightness: 0.5 < backgroundColorProps.l ? 0.5 : 1
			});
		}

		return this.allColors.pluck({ // We want our color to have a minimum contrast of 7:1 with a white background.
			color: wp.customize( this.params.choices.backgroundColor ).get(),
			minContrast: backgroundMinContrast
		}).pluck({ // We want our color to have a minimum contrast of 3:1 with surrounding black text.
			color: wp.customize( this.params.choices.textColor ).get(),
			minContrast: surroundingTextMinContrast
		})
		.sortBy( 's' ) // Sort colors by contrast.
		.getHexArray();
	},

	/**
	 * Updates the control.allColors and control.colors attributes.
	 *
	 * @since 1.0
	 * @param {bool} updateValue - Whether we should update the selection or not.
	 * @returns {void}
	 */
	updateColors: function( updateValue ) {
		var i;
		this.allColors  = false;
		this.colors     = {
			AAA: this.queryColors( 'AAA' ),
			AA: this.queryColors( 'AA' ),
			A: this.queryColors( 'A' )
		};

		// Remove duplicates from AA list.
		for ( i = 0; i < this.colors.AAA.length; i++ ) {
			this.colors.AA.splice( this.colors.AA.indexOf( this.colors.AAA[ i ] ), 1 );
		}
		if ( updateValue ) {
			this.updateColorValue();
		}
	},

	/**
	 * Watch defined controls and re-trigger results calculations when there's a change.
	 *
	 * @since 1.0
	 * @param {string} settingToWatch - The setting we're watching. This can either be the background or the text color.
	 * @returns {void}
	 */
	watchSetting: function( settingToWatch ) {
		var control = this;

		wp.customize( settingToWatch, function( setting ) {
			setting.bind( function() {
				control.updateColors( true );
			});
		});

		if ( -1 < settingToWatch.indexOf( '[' ) ) {
			wp.customize( settingToWatch.split( '[' )[0], function( setting ) {
				setting.bind( function() {
					control.updateColors( true );
				});
			});
		}
	},

	/**
	 * Get the best available color for a11y.
	 *
	 * @since 1.0
	 * @returns {string}
	 */
	getBest: function() {
		if ( this.colors.AAA[0] ) {
			return this.colors.AAA[0];
		}
		if ( this.colors.AA[0] ) {
			return this.colors.AA[0];
		}
		if ( this.colors.A[0] ) {
			return this.colors.A[0];
		}
	},

	/**
	 * Update the control value.
	 *
	 * @since 1.0
	 * @returns void
	 */
	updateColorValue: function() {
		if ( this.getBest() ) {
			this.container.find( '.hidden-value-hex' ).attr( 'value', this.getBest() ).trigger( 'change' );
		}
	}
});

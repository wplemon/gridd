/* jshint -W098 */
/**
 * Based on the kirkiSetSettingValue object with some minor tweaks.
 */
var griddSetSettingValue = { // eslint-disable-line no-unused-vars

	/**
	 * Set the value of the control.
	 *
	 * @since 3.0.0
	 * @param string setting The setting-ID.
	 * @param mixed  value   The value.
	 */
	set: function( setting, value ) {

		/**
		 * Get the control of the sub-setting.
		 * This will be used to get properties we need from that control,
		 * and determine if we need to do any further work based on those.
		 */
		var $this   = this,
			control = wp.customize.settings.controls[ setting ],
			valueJSON;

		// If the control doesn't exist then return.
		if ( _.isUndefined( control ) ) {
			return true;
		}

		if ( ! control.id ) {
			return;
		}

		if (
			'background_preset' === setting ||
			'background_repeat' === setting ||
			'background_repeat' === setting ||
			'background_size' === setting ||
			'background_attachment' === setting
		) {
			$this.setValue( setting, value );
		}

		// Process visually changing the value based on the control type.
		switch ( control.type ) {

			case 'kirki-background':
				if ( ! _.isUndefined( value['background-color'] ) ) {
					$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value['background-color'] );
				}
				$this.findElement( setting, '.placeholder, .thumbnail' ).removeClass().addClass( 'placeholder' ).html( 'No file selected' );
				_.each( [ 'background-repeat', 'background-position' ], function( subVal ) {
					if ( ! _.isUndefined( value[ subVal ] ) ) {
						$this.setSelectWoo( $this.findElement( setting, '.' + subVal + ' select' ), value[ subVal ] );
					}
				});
				_.each( [ 'background-size', 'background-attachment' ], function( subVal ) {
					jQuery( $this.findElement( setting, '.' + subVal + ' input[value="' + value + '"]' ) ).prop( 'checked', true );
				});
				valueJSON = JSON.stringify( value ).replace( /'/g, '&#39' );
				jQuery( $this.findElement( setting, '.background-hidden-value' ).attr( 'value', valueJSON ) ).trigger( 'change' );
				break;

			case 'kirki-code':
				jQuery( $this.findElement( setting, '.CodeMirror' ) )[0].CodeMirror.setValue( value );
				break;

			case 'checkbox':
			case 'kirki-switch':
			case 'kirki-toggle':
				value = ( 1 === value || '1' === value || true === value ) ? true : false;
				jQuery( $this.findElement( setting, 'input' ) ).prop( 'checked', value );
				wp.customize.instance( setting ).set( value );
				break;

			case 'kirki-select':
			case 'kirki-fontawesome':
				$this.setSelectWoo( $this.findElement( setting, 'select' ), value );
				break;

			case 'kirki-slider':
				jQuery( $this.findElement( setting, 'input' ) ).prop( 'value', value );
				jQuery( $this.findElement( setting, '.kirki_range_value .value' ) ).html( value );
				break;

			case 'kirki-generic':
				if ( _.isUndefined( control.choices ) || _.isUndefined( control.choices.element ) ) {
					control.choices.element = 'input';
				}
				jQuery( $this.findElement( setting, control.choices.element ) ).prop( 'value', value );
				break;

			case 'kirki-color':
				$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value );
				break;

			case 'kirki-multicheck':
				$this.findElement( setting, 'input' ).each( function() {
					jQuery( this ).prop( 'checked', false );
				});
				_.each( value, function( subValue, i ) {
					jQuery( $this.findElement( setting, 'input[value="' + value[ i ] + '"]' ) ).prop( 'checked', true );
				});
				break;

			case 'kirki-multicolor':
				_.each( value, function( subVal, index ) {
					$this.setColorPicker( $this.findElement( setting, '.multicolor-index-' + index ), subVal );
				});
				break;

			case 'kirki-radio-buttonset':
			case 'kirki-radio-image':
			case 'kirki-radio':
			case 'kirki-dashicons':
			case 'kirki-color-palette':
			case 'kirki-palette':
				jQuery( $this.findElement( setting, 'input[value="' + value + '"]' ) ).prop( 'checked', true );
				break;

			case 'kirki-typography':
				_.each( [ 'font-family', 'variant' ], function( subVal ) {
					if ( ! _.isUndefined( value[ subVal ] ) ) {
						$this.setSelectWoo( $this.findElement( setting, '.' + subVal + ' select' ), value[ subVal ] );
					}
				});
				_.each( [ 'font-size', 'line-height', 'letter-spacing', 'word-spacing' ], function( subVal ) {
					if ( ! _.isUndefined( value[ subVal ] ) ) {
						jQuery( $this.findElement( setting, '.' + subVal + ' input' ) ).prop( 'value', value[ subVal ] );
					}
				});

				if ( ! _.isUndefined( value.color ) ) {
					$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value.color );
				}
				valueJSON = JSON.stringify( value ).replace( /'/g, '&#39' );
				jQuery( $this.findElement( setting, '.typography-hidden-value' ).attr( 'value', valueJSON ) ).trigger( 'change' );
				break;

			case 'kirki-dimensions':
				_.each( value, function( subValue, id ) {
					jQuery( $this.findElement( setting, '.' + id + ' input' ) ).prop( 'value', subValue );
				});
				break;

			case 'kirki-repeater':

				// Not yet implemented.
				break;

			case 'kirki-custom':

				// Do nothing.
				break;

			case 'gridd_grid':
				if ( 'string' === typeof value ) {
					value = JSON.parse( value );
				}
				wp.customize.control( 'gridd_grid' ).gridVal = value;
				wp.customize.control( 'gridd_grid' ).drawGridSelector();
				wp.customize.control( 'gridd_grid' ).drawColumnFields();
				wp.customize.control( 'gridd_grid' ).drawRowFields();
				wp.customize.control( 'gridd_grid' ).drawGridSelectedParts();
				wp.customize.control( 'gridd_grid' ).saveValue();
				break;
			default:
				jQuery( $this.findElement( setting, 'input' ) ).prop( 'value', value );
		}
	},

	/**
	 * Set the value for colorpickers.
	 * CAUTION: This only sets the value visually, it does not change it in th wp object.
	 *
	 * @since 3.0.0
	 * @param object selector jQuery object for this element.
	 * @param string value    The value we want to set.
	 */
	setColorPicker: function( selector, value ) {
		selector.attr( 'data-default-color', value ).data( 'default-color', value ).wpColorPicker( 'color', value );
	},

	/**
	 * Sets the value in a selectWoo element.
	 * CAUTION: This only sets the value visually, it does not change it in th wp object.
	 *
	 * @since 3.0.0
	 * @param string selector The CSS identifier for this selectWoo.
	 * @param string value    The value we want to set.
	 */
	setSelectWoo: function( selector, value ) {
		jQuery( selector ).selectWoo().val( value ).trigger( 'change' );
	},

	/**
	 * Sets the value in textarea elements.
	 * CAUTION: This only sets the value visually, it does not change it in th wp object.
	 *
	 * @since 3.0.0
	 * @param string selector The CSS identifier for this textarea.
	 * @param string value    The value we want to set.
	 */
	setTextarea: function( selector, value ) {
		jQuery( selector ).prop( 'value', value );
	},

	/**
	 * Finds an element inside this control.
	 *
	 * @since 3.0.0
	 * @param string setting The setting ID.
	 * @param string element The CSS identifier.
	 */
	findElement: function( setting, element ) {
		return wp.customize.control( setting ).container.find( element );
	},

	/**
	 * Updates the value in the wp.customize object.
	 *
	 * @since 3.0.0
	 * @param string setting The setting-ID.
	 * @param mixed  value   The value.
	 */
	setValue: function( setting, value, timeout ) {
		timeout = ( _.isUndefined( timeout ) ) ? 100 : parseInt( timeout, 10 );
		wp.customize.instance( setting ).set({});
		setTimeout( function() {
			wp.customize.instance( setting ).set( value );
		}, timeout );
	}
};

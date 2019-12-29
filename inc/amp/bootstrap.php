<?php
/**
 * Partial AMP support.
 *
 * @package gridd
 */

use Gridd\Style;

/**
 * Filter element attributes.
 *
 * @since 1.0.0
 * @param array  $atts           The element attributes we want to print.
 * @param string $filter_context Argument passed-on as the 2nd param in the gridd_print_attributes filter.
 * @return array
 */
add_filter(
	'gridd_print_attributes',
	function( $atts, $filter_context ) {
		if ( 0 === strpos( $filter_context, 'navigation-' ) ) {
			$nav_id = str_replace( 'navigation-', '', $filter_context );
			if ( is_numeric( $nav_id ) ) {
				$nav_id = (int) $nav_id;
				$mode   = get_theme_mod( "nav_{$nav_id}_vertical", false ) ? 'vertical' : 'horizontal';
				// See https://amp-wp.org/documentation/playbooks/toggling-hamburger-menus/.
				$atts['[class]']         = "'navigation gridd-nav-{$mode}' + ( navMenuExpanded{$nav_id} ? ' active' : '' )";
				$atts['[aria-expanded]'] = "navMenuExpanded{$nav_id} ? 'true' : 'false'";
			}
		}
		return $atts;
	},
	10,
	2
);

/**
 * Add styles.
 *
 * @since 1.0.0
 * @return void
 */
add_action(
	'wp_head',
	function() {
		$style = Style::get_instance( 'main-styles' );
		$style->add_file( get_template_directory() . '/inc/amp/assets/css/amp.css' );
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			$style->add_file( get_template_directory() . '/inc/amp/assets/css/amp-edd.css' );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			$style->add_file( get_template_directory() . '/inc/amp/assets/css/amp-woo.css' );
		}
	}
);

/**
 * Add body class.
 *
 * @since 1.0.0
 * @param array $classes An array of body classes.
 * @return array
 */
add_filter(
	'body_class',
	function( $classes ) {
		$classes[] = 'gridd-amp';
		return $classes;
	}
);

/**
 * Generates the HTML for a toggle button.
 *
 * @since 1.0
 * @param string $html The button HTML.
 * @param array $args  The button arguments.
 * @return string
 */
add_filter(
	'gridd_get_toggle_button',
	function( $html, $args ) {

		$html = '';

		// Create new state for managing storing the whether the sub-menu is expanded.
		$html .= '<amp-state id="' . esc_attr( $args['expanded_state_id'] ) . '">';
		$html .= '<script type="application/json">' . $args['expanded'] . '</script>';
		$html .= '</amp-state>';

		if ( ! isset( $args['classes'] ) ) {
			$args['classes'] = [];
		}
		$args['classes'][] = 'gridd-toggle';
		$classes           = implode( ' ', array_unique( $args['classes'] ) );

		$button_atts = [
			'aria-expanded' => 'false',
		];

		$button_atts['[class]']         = '(' . $args['expanded_state_id'] . '?\'' . $classes . ' toggled-on\':\'' . $classes . '\')';
		$button_atts['[aria-expanded]'] = "{$args['expanded_state_id']} ? 'true' : 'false'";
		$button_atts['on']              = "tap:AMP.setState({ {$args['expanded_state_id']}: ! {$args['expanded_state_id']} })";

		/*
		* Create the toggle button which mutates the state and which has class and
		* aria-expanded attributes which react to the state changes.
		*/
		$html .= '<button class="' . $classes . '"';
		foreach ( $button_atts as $key => $val ) {
			if ( ! empty( $key ) ) {
				$html .= ' ' . $key . '="' . $val . '"';
			}
		}
		$html .= '>';

		if ( isset( $args['screen_reader_label_collapse'] ) && isset( $args['screen_reader_label_expand'] ) ) {

			// Let the screen reader text in the button also update based on the expanded state.
			$html .= '<span class="screen-reader-text"';
			$html .= ' [text]="' . $args['expanded_state_id'] . '?\'' . esc_attr( $args['screen_reader_label_collapse'] ) . '\':\'' . esc_attr( $args['screen_reader_label_expand'] ) . '\'">';
			$html .= esc_html( $args['screen_reader_label_expand'] );
		} elseif ( isset( $args['screen_reader_label_toggle'] ) ) {
			$html .= '<span class="screen-reader-text">' . $args['screen_reader_label_toggle'] . '</span>';
		}

		$html .= '</span>';
		$html .= $args['label'];
		$html .= '</button>';

		return $html;
	},
	10,
	2
);

/**
 * WooCommerce tweaks.
 *
 * @since 1.0.0
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_dequeue_script( 'wc-cart-fragments' ); // Disables AJAX calls.
	},
	11
);

/**
 * Adds additional css-vars in the <head> of the document.
 * In non-AMP sites these get calculated via JS so this is just a fallback.
 * It won't always be 100% correct (that's why we were using JS to calculate them)
 * but it will be a pretty close approximation.
 *
 * @since 1.0.0
 * @return void
 */
add_action(
	'wp_head',
	function() {
		$content_max_width                  = get_theme_mod( 'content_max_width', '45em' );
		$main_font_size                     = get_theme_mod( 'gridd_body_font_size', 18 ) . 'px';
		$fluid_typo_ratio                   = get_theme_mod( 'gridd_fluid_typography_ratio', 0.25 );
		$gridd_content_max_width_calculated = $content_max_width;

		// If we're using "em" for the content area'smax-width,
		// then we needto make some calculations for the $mw-c css-var.
		if ( false === strpos( $content_max_width, 'rem' ) && false !== strpos( $content_max_width, 'em' ) ) {

			// Check that there are numbers in our value and that we're not using calc.
			if ( preg_match( '#[0-9]#', $main_font_size ) ) {

				// This is an approximation. We'll be multiplying the ratio to get an average size.
				$ratio = 1 + 2 * $fluid_typo_ratio;

				// Get the numeric value for the font-size.
				$font_size_numeric = filter_var( $main_font_size, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

				// Get the numeric value for the content's max-width.
				$content_max_width_numeric = filter_var( $content_max_width, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

				// Fallback to pixels for the font-size.
				$font_size_unit = 'px';

				// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
				$units = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];
				foreach ( $units as $unit ) {
					if ( false !== strpos( $main_font_size, $unit ) ) {
						$font_size_unit = $unit;
						break;
					}
				}

				$gridd_content_max_width_calculated = ( $content_max_width_numeric * $ratio * $font_size_numeric ) . $font_size_unit;
			}
		}

		// We use esc_attr() for sanitization here since we want to sanitize a CSS value.
		// Though not strictly accurate, in this case it is secure and doesn't cause any issues.
		echo '<style>body{--mw-c:' . esc_attr( $gridd_content_max_width_calculated ) . ';</style>';
	}
);

/**
 * Add theme-support for AMP Native mode.
 *
 * @since 1.0.0
 */
add_action(
	'after_setup_theme',
	function() {
		add_theme_support(
			'amp',
			[
				'paired' => false,
			]
		);
	}
);

/**
 * Disable AMP in the customizer.
 *
 * @since 1.0.0
 */
if ( is_customize_preview() ) {
	add_filter( 'amp_is_enabled', '__return_false' );
}

/**
 * Disable REST-API calls for the gridd theme.
 *
 * @since 1.0.0
 */
add_filter( 'gridd_disable_rest', '__return_true' );

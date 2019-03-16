<?php
/**
 * Customizer options.
 *
 * @package Gridd
 */

use Gridd\Grid_Part\Navigation;
use Gridd\AMP;
use Gridd\Customizer;
use Gridd\Customizer\Sanitize;
use Gridd\Theme;

/**
 * Register the menus.
 *
 * @since 1.0
 * @return void
 */
function gridd_add_nav_parts() {
	$number = Navigation::get_number_of_nav_menus();

	for ( $i = 1; $i <= $number; $i++ ) {
		gridd_nav_customizer_options( $i );
	}
}
add_action( 'after_setup_theme', 'gridd_add_nav_parts' );

/**
 * This function creates all options for a navigation.
 * We use a parameter since we'll allow multiple navigations.
 *
 * @since 1.0
 * @param int $id The number of this navigation.
 * @return void
 */
function gridd_nav_customizer_options( $id ) {

	$sanitization = new Sanitize();

	/**
	 * Add Customizer Sections.
	 */
	Customizer::add_outer_section(
		"gridd_grid_part_details_nav_$id",
		[
			'title'       => sprintf(
				/* translators: The grid-part label. */
				esc_html__( '%s Options', 'gridd' ),
				/* translators: The navigation number. */
				sprintf( esc_html__( 'Navigation %d', 'gridd' ), absint( $id ) )
			),
			'description' => Customizer::section_description(
				"gridd_grid_part_details_nav_$id",
				[
					'plus' => [
						esc_html__( 'Selecting from an array of WCAG-compliant colors for text', 'gridd' ),
						esc_html__( 'WooCommerce Cart inside the navigation', 'gridd' ),
						esc_html__( 'font-size adjustments', 'gridd' ),
					],
					'tip'  => '<p>' . esc_html__( 'Try to keep your horizontal navigation as short as possible and avoid hierarchical menus.', 'gridd' ) . '</p><p>' . esc_html__( 'Verical menus provide a better user experience for hierarchical (nested) menus.', 'gridd' ) . '</p>',
					'docs' => 'https://wplemon.com/documentation/gridd/grid-parts/navigation/',
				]
			),
		]
	);

	/**
	 * Focus on menu_locations section.
	 */
	Customizer::add_field(
		[
			'settings' => "gridd_logo_focus_on_menu_locations_$id",
			'type'     => 'custom',
			'label'    => esc_html__( 'Looking for your menu items?', 'gridd' ),
			'section'  => "gridd_grid_part_details_nav_$id",
			'default'  => '<div style="margin-bottom:1em;"><button class="button-gridd-focus global-focus button button button-large" data-context="section" data-focus="menu_locations">' . esc_html__( 'Click here to edit your menus', 'gridd' ) . '</button></div>',
		]
	);

	Customizer::add_field(
		[
			'type'              => 'radio',
			'settings'          => "gridd_grid_nav_{$id}_responsive_behavior",
			'label'             => esc_html__( 'Responsive Behavior', 'gridd' ),
			'description'       => sprintf(
				/* translators: The link properies. */
				__( 'Select how this navigation should behave in smaller screens. We recommend you hide navigations on mobile and instead use the <a%s>separate mobile-navigation menu</a>.', 'gridd' ),
				' href="#" class="button-gridd-focus global-focus" data-context="section" data-focus="gridd_grid_part_details_nav-handheld"'
			),
			'section'           => "gridd_grid_part_details_nav_$id",
			'default'           => 'desktop-normal mobile-hidden',
			'choices'           => [
				'desktop-normal mobile-normal' => esc_html__( 'Always visible', 'gridd' ),
				'desktop-normal mobile-icon'   => esc_html__( 'Collapse to icon on mobile', 'gridd' ),
				'desktop-icon mobile-icon'     => esc_html__( 'Always collapsed', 'gridd' ),
				'desktop-normal mobile-hidden' => esc_html__( 'Hide on mobile', 'gridd' ),
			],
			'sanitize_callback' => function( $value ) {
				if ( 'desktop-normal mobile-normal' !== $value && 'desktop-normal mobile-icon' !== $value && 'desktop-icon mobile-icon' !== $value && 'desktop-normal mobile-hidden' !== $value ) {
					return 'desktop-normal mobile-hidden';
				}
				return $value;
			},
		]
	);

	Customizer::add_field(
		[
			'type'        => 'dimension',
			'settings'    => "gridd_grid_nav_{$id}_padding",
			'label'       => esc_html__( 'Padding', 'gridd' ),
			'description' => __( 'Inner padding for this grid-part. Use any valid CSS value. For details on how padding works, please refer to <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/padding" target="_blank" rel="nofollow">this article</a>.', 'gridd' ),
			'section'     => "gridd_grid_part_details_nav_$id",
			'default'     => '1em',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-nav-$id-padding",
		]
	);

	Customizer::add_field(
		[
			'type'        => 'color',
			'label'       => esc_html__( 'Background Color', 'gridd' ),
			'description' => esc_html__( 'Controls the overall background color for this grid-part.', 'gridd' ),
			'settings'    => "gridd_grid_nav_{$id}_bg_color",
			'section'     => "gridd_grid_part_details_nav_$id",
			'default'     => '#ffffff',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-nav-$id-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'label'             => esc_html__( 'Items Color', 'gridd' ),
			'description'       => esc_html__( 'Select the color used for your menu items. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
			'settings'          => "gridd_grid_nav_{$id}_items_color",
			'section'           => "gridd_grid_part_details_nav_$id",
			'choices'           => [
				'setting' => "gridd_grid_nav_{$id}_bg_color",
			],
			'default'           => '#000000',
			'transport'         => 'postMessage',
			'css_vars'          => "--gridd-nav-$id-color",
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	Customizer::add_field(
		[
			'type'        => 'color',
			'label'       => esc_html__( 'Accent Background Color', 'gridd' ),
			'description' => esc_html__( 'Controls the background-color for menu items on hover, as well as for the currently active menu-item. Applies to both parent and child (dropdown) menu items.', 'gridd' ),
			'settings'    => "gridd_grid_nav_{$id}_accent_bg_color",
			'section'     => "gridd_grid_part_details_nav_$id",
			'default'     => '#0f5e97',
			'transport'   => 'postMessage',
			'css_vars'    => "--gridd-nav-$id-accent-bg",
			'choices'     => [
				'alpha' => true,
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'gridd-wcag-tc',
			'label'             => esc_html__( 'Accent Items Color', 'gridd' ),
			'description'       => esc_html__( 'Select the color used for your menu items on hover, as well as for the currently active menu-item. Applies to both parent and child (dropdown) menu items. Please choose a color with sufficient contrast with the selected background-color.', 'gridd' ),
			'settings'          => "gridd_grid_nav_{$id}_accent_color",
			'section'           => "gridd_grid_part_details_nav_$id",
			'choices'           => [
				'setting' => "gridd_grid_nav_{$id}_accent_bg_color",
			],
			'default'           => '#ffffff',
			'transport'         => 'postMessage',
			'css_vars'          => "--gridd-nav-$id-accent-color",
			'sanitize_callback' => [ $sanitization, 'color_hex' ],
		]
	);

	Customizer::add_field(
		[
			'type'            => 'checkbox',
			'settings'        => "gridd_grid_nav_{$id}_vertical",
			'label'           => esc_html__( 'Enable Vertical Menu Mode', 'gridd' ),
			'description'     => esc_html__( 'If your layout is column-based and you want a vertical side-navigation enable this option.', 'gridd' ),
			'section'         => "gridd_grid_part_details_nav_$id",
			'default'         => false,
			'active_callback' => [
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-icon mobile-icon',
					'operator' => '!==',
				],
			],
		]
	);

	Customizer::add_field(
		[
			'type'              => 'radio-buttonset',
			'settings'          => "gridd_grid_nav_{$id}_justify_content",
			'label'             => esc_html__( 'Justify Items', 'gridd' ),
			'description'       => esc_html__( 'Choose how menu items will be spread horizontally inside the menu container.', 'gridd' ),
			'tooltip'           => esc_html__( 'This helps distribute extra free space left over when all the items on a line have reached their maximum size. It also exerts some control over the alignment of items when they overflow the line.', 'gridd' ),
			'section'           => "gridd_grid_part_details_nav_$id",
			'default'           => 'center',
			'transport'         => 'postMessage',
			'css_vars'          => "--gridd-nav-$id-justify",
			'active_callback'   => [
				[
					'setting'  => "gridd_grid_nav_{$id}_vertical",
					'operator' => '!==',
					'value'    => true,
				],
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-icon mobile-icon',
					'operator' => '!==',
				],
			],
			'choices'           => [
				'flex-start'    => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'Start', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'Start', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-flex-start.svg' ) . '</span>',
				'flex-end'      => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'End', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'End', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-flex-end.svg' ) . '</span>',
				'center'        => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'Center', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'Center', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-center.svg' ) . '</span>',
				'space-between' => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'Space Between', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'Space Between', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-space-between.svg' ) . '</span>',
				'space-around'  => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'Space Around', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'Space Around', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-space-around.svg' ) . '</span>',
				'space-evenly'  => '<span class="gridd-flexbox-svg-option" title="' . esc_attr__( 'Space Evenly', 'gridd' ) . '"><span class="screen-reader-text">' . esc_html__( 'Space Evenly', 'gridd' ) . '</span>' . Theme::get_fcontents( 'assets/images/flexbox/justify-content-space-evenly.svg' ) . '</span>',
			],
			'sanitize_callback' => function( $value ) {
				if ( ! in_array( $value, [ 'flex-start', 'flex-end', 'center', 'space-between', 'space-around', 'space-evenly' ], true ) ) {
					return 'center';
				}
				return $value;
			},
		]
	);

	Customizer::add_field(
		[
			'type'              => 'text',
			'settings'          => "gridd_grid_nav_{$id}_expand_label",
			'label'             => esc_html__( 'Expand Label', 'gridd' ),
			'section'           => "gridd_grid_part_details_nav_$id",
			'default'           => esc_html__( 'MENU', 'gridd' ),
			'transport'         => 'refresh',
			'active_callback'   => [
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-normal',
					'operator' => '!==',
				],
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-hidden',
					'operator' => '!==',
				],
			],
			'sanitize_callback' => 'esc_html',
		]
	);

	Customizer::add_field(
		[
			'type'              => 'radio-buttonset',
			'settings'          => "gridd_grid_nav_{$id}_expand_icon",
			'label'             => esc_html__( 'Expand Icon', 'gridd' ),
			'description'       => esc_html__( 'Select the icon that should be used to expand the navigation.', 'gridd' ),
			'section'           => "gridd_grid_part_details_nav_$id",
			'default'           => 'menu-1',
			'transport'         => 'refresh',
			'choices'           => Navigation::get_expand_svgs(),
			'active_callback'   => [
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-normal',
					'operator' => '!==',
				],
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-hidden',
					'operator' => '!==',
				],
			],
			'sanitize_callback' => function( $value ) {
				return in_array( $value, array_keys( Navigation::get_expand_svgs() ), true ) ? $value : 'menu-1';
			},
		]
	);

	Customizer::add_field(
		[
			'type'              => 'select',
			'settings'          => "gridd_grid_nav_{$id}_expand_icon_position",
			'label'             => esc_html__( 'Expand Icon Position', 'gridd' ),
			'description'       => esc_html__( 'Changes the position of the collapsed-menu icon inside the area.', 'gridd' ),
			'section'           => "gridd_grid_part_details_nav_$id",
			'default'           => 'center-right',
			'transport'         => 'refresh',
			'choices'           => [
				'top-left'      => esc_html__( 'Top Left', 'gridd' ),
				'top-center'    => esc_html__( 'Top Center', 'gridd' ),
				'top-right'     => esc_html__( 'Top Right', 'gridd' ),
				'center-left'   => esc_html__( 'Center Left', 'gridd' ),
				'center-center' => esc_html__( 'Center Center', 'gridd' ),
				'center-right'  => esc_html__( 'Center Right', 'gridd' ),
				'bottom-left'   => esc_html__( 'Bottom Left', 'gridd' ),
				'bottom-center' => esc_html__( 'Bottom Center', 'gridd' ),
				'bottom-right'  => esc_html__( 'Bottom Right', 'gridd' ),
			],
			'active_callback'   => [
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-normal',
					'operator' => '!==',
				],
				[
					'setting'  => "gridd_grid_nav_{$id}_responsive_behavior",
					'value'    => 'desktop-normal mobile-hidden',
					'operator' => '!==',
				],
			],
			'sanitize_callback' => function( $value ) {
				if ( ! in_array( $value, [ 'top-left', 'top-center', 'top-right', 'center-left', 'center-center', 'center-right', 'bottom-left', 'bottom-center', 'bottom-right' ], true ) ) {
					return 'center-right';
				}
				return $value;
			},
		]
	);
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

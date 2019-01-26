<?php
/**
 * Customizer options for handheld nav.
 *
 * @package Gridd
 */

gridd_add_customizer_section(
	'gridd_grid_part_details_nav-handheld',
	[
		'title'       => esc_html__( 'Mobile Navigation', 'gridd' ),
		'description' => sprintf(
			'<div class="gridd-section-description">%1$s%2$s</div>',
			( ! Gridd::is_pro() ) ? '<div class="gridd-go-plus">' . __( '<a href="https://wplemon.com/gridd-plus" rel="nofollow" target="_blank">Upgrade to <strong>plus</strong></a> to hide labels and only make them available to screen-readers.', 'gridd' ) . '</div>' : '',
			'<div class="gridd-docs"><a href="https://wplemon.com/documentation/gridd/grid-parts/mobile-navigation/" target="_blank" rel="noopener noreferrer nofollow">' . esc_html__( 'Learn more about these settings', 'gridd' ) . '</a></div>'
		),
		'priority'    => 26,
		'panel'       => 'gridd_options',
	]
);

// The parts available for handheld-nav.
$parts = [
	'menu'        => esc_html__( 'Menu', 'gridd' ),
	'home'        => esc_html__( 'Home', 'gridd' ),
	'widget-area' => esc_html__( 'Widget Area', 'gridd' ),
	'search'      => esc_html__( 'Search', 'gridd' ),
];

// If WooCommerce is installed, add another item for the Cart.
if ( class_exists( 'WooCommerce' ) ) {
	$parts['woo-cart'] = esc_html__( 'Cart', 'gridd' );
}

gridd_add_customizer_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_grid_nav-handheld_enable',
		'label'           => esc_html__( 'Enable Mobile Navigation', 'gridd' ),
		'description'     => esc_html__( 'Enables the mobile navigation for devices smaller than the breakpoint defined in your grid settings.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
		'default'         => true,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_nav-handheld_enable_template' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'sortable',
		'settings'        => 'gridd_grid_nav-handheld_parts',
		'label'           => esc_html__( 'Mobile Navigation active parts & order', 'gridd' ),
		'description'     => esc_html__( 'Enable and disable parts of the mobile navigation, and reorder them at will.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
		'default'         => class_exists( 'WooCommerce' ) ? [ 'menu', 'home', 'search', 'woo-cart' ] : [ 'menu', 'home', 'search' ],
		'choices'         => $parts,
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'grid_part_handheld_parts' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'            => 'text',
		'settings'        => 'gridd_grid_nav-handheld_widget_area_label',
		'label'           => esc_attr__( 'Widget Area Button Label', 'gridd' ),
		'description'     => esc_html__( 'Please add a label for the widget area.', 'gridd' ),
		'section'         => 'gridd_grid_part_details_nav-handheld',
		'default'         => esc_html__( 'Settings', 'gridd' ),
		'transport'       => 'postMessage',
		'partial_refresh' => [
			'gridd_grid_nav-handheld_widget_area_label_template' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback' => [
			[
				'setting'  => 'gridd_grid_nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
			[
				'setting'  => 'gridd_grid_nav-handheld_parts',
				'operator' => 'contains',
				'value'    => 'widget-area',
			],
		],
	]
);

gridd_add_customizer_field(
	[
		'type'              => 'textarea',
		'settings'          => 'gridd_grid_nav-handheld_widget_area_icon',
		'label'             => esc_attr__( 'Widget Area Button SVG Icon', 'gridd' ),
		'description'       => __( 'Paste SVG code for the icon you want to use. You can find a great collection of icons on the <a href="https://iconmonstr.com/" target="_blank" rel="noopener noreferrer nofollow">iconmonstr website</a> or add your custom icon.', 'gridd' ),
		'section'           => 'gridd_grid_part_details_nav-handheld',
		'default'           => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z"/></svg>',
		'transport'         => 'postMessage',
		'sanitize_callback' => function( $value ) {
			return $value;
		},
		'partial_refresh'   => [
			'grid_part_handheld_widget_area_icon' => [
				'selector'            => '.gridd-tp-nav-handheld',
				'container_inclusive' => true,
				'render_callback'     => function() {
					do_action( 'gridd_the_grid_part', 'nav-handheld' );
				},
			],
		],
		'active_callback'   => [
			[
				'setting'  => 'gridd_grid_nav-handheld_parts',
				'operator' => 'contains',
				'value'    => 'widget-area',
			],
			[
				'setting'  => 'gridd_grid_nav-handheld_enable',
				'operator' => '===',
				'value'    => true,
			],
		],
	]
);

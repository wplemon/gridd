<?php
/**
 * Customizer Blog Options.
 *
 * @package Gridd
 */

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

gridd_add_customizer_field(
	[
		'type'        => 'dimension',
		'settings'    => 'gridd_woocommerce_product_catalog_min_width',
		'label'       => esc_html__( 'Products min-width', 'gridd' ),
		'description' => '',
		'section'     => 'woocommerce_product_catalog',
		'transport'   => 'auto',
		'default'     => '250px',
		'css_vars'    => '--gridd-woo-catalog-product-min-width',
	]
);

gridd_add_customizer_field(
	[
		'type'        => 'slider',
		'settings'    => 'gridd_woocommerce_product_catalog_per_page',
		'label'       => esc_html__( 'Products per-page', 'gridd' ),
		'description' => '',
		'section'     => 'woocommerce_product_catalog',
		'default'     => 12,
		'choices'     => [
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		],
	]
);

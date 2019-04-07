<?php
/**
 * Extra settings for the static_front_page section.
 *
 * @package Gridd
 * @since 1.1.1
 */

use Gridd\Customizer;

Customizer::add_field(
	[
		'type'            => 'checkbox',
		'settings'        => 'gridd_hide_home_title',
		'label'           => esc_attr__( 'Hide Homepage Title', 'gridd' ),
		'section'         => 'static_front_page',
		'default'         => true,
		'transport'       => 'refresh',
		'priority'        => 100,
		'active_callback' => function() {
			return 'page' === get_option( 'show_on_front', 'posts' );
		},
	]
);

/* Omit closing PHP tag to avoid "Headers already sent" issues. */

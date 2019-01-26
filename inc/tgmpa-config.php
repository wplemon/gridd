<?php
/**
 * TGMPA Configuration.
 *
 * @package Gridd
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

/**
 * Register the required plugins for this theme.
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 *
 * @since 1.0
 */
function gridd_register_required_plugins() {
	tgmpa(
		[
			[
				'name'     => 'Kirki',
				'slug'     => 'kirki',
				'required' => true,
			],

		],
		[
			'id'           => 'gridd',
			'menu'         => 'gridd-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => true,
			'message'      => '',
		]
	);
}
add_action( 'tgmpa_register', 'gridd_register_required_plugins' );

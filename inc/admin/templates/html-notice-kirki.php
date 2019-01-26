<?php
/**
 * Admin View: Notice - Kirki.
 *
 * @package Gridd
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Kirki' ) ) {
	return;
}

$installed_plugins  = get_plugins();
$kirki_is_installed = ( isset( $installed_plugins['kirki/kirki.php'] ) );
?>
<div id="gridd-error-no-kirki" class="notice notice-error">
	<?php if ( ! $kirki_is_installed ) : ?>
		<?php
		if ( current_user_can( 'install_plugins' ) ) {
			$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=kirki' ), 'install-plugin_kirki' );
		} else {
			$url = 'https://wordpress.org/plugins/kirki/';
		}
		?>
		<p><?php esc_html_e( 'We noticed the Kirki plugin is not installed. The Gridd theme requires this plugin to properly function', 'gridd' ); ?></p>
		<p><a href="<?php echo esc_url( $url ); ?>" class="button button-primary"><?php esc_html_e( 'Install the Kirki plugin', 'gridd' ); ?></a></p>
	<?php else : ?>
		<p><?php esc_html_e( 'We noticed the Kirki plugin is not activated. The Gridd theme requires this plugin to properly function.', 'gridd' ); ?></p>
		<p><a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=kirki/kirki.php&plugin_status=active' ), 'activate-plugin_kirki/kirki.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Activate the Kirki plugin', 'gridd' ); ?></a></p>
	<?php endif; ?>
</div>

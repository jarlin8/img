<?php defined( 'ABSPATH' ) || exit; ?>

<input type="text" name="<?php echo esc_attr( $data['id'] ); ?>" id="<?php echo esc_attr( $data['id'] ); ?>" value="AB12-CD34-EF56-GH78" class="regular-text" placeholder="XXXX-XXXX-XXXX-XXXX" data-key="<?php echo esc_attr( get_option( $data['id'], '' ) ); ?>" data-key-masked="<?php echo esc_attr( $this->mask_license_key( get_option( $data['id'], '' ) ) ); ?>">

<?php if ( get_option( $data['id'] ) ) : ?>
	<code style="background:#46b450;color:#fff"><?php echo esc_html_x( 'Active', 'License key is active', 'shortcodes-ultimate-extra' ); ?></code>
<?php else : ?>
	<code><?php echo esc_html_x( 'Inactive', 'License key is not active', 'shortcodes-ultimate-extra' ); ?></code>
<?php endif; ?>

<p class="description">
	<?php esc_html_e( 'Enter license key to enable automatic updates', 'shortcodes-ultimate-extra' ); ?>.
	<a href="https://getshortcodes.com/docs/extra-shortcodes-user-guide/#activating-the-license" target="_blank"><?php esc_html_e( 'How to activate license key', 'shortcodes-ultimate-extra' ); ?></a>.
</p>

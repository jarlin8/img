<?php defined( '\ABSPATH' ) || exit; ?>
<h2>Content Egg <?php esc_html_e( 'activation', 'content-egg' ) ?></h2>
<?php settings_errors(); ?>

<p><?php esc_html_e( 'In order to receive all benefits of Contennt Egg, you need to activate your copy of the plugin.', 'content-egg' ); ?></p>
<p><?php esc_html_e( 'By activating Contennt Egg license you will unlock premium options - direct plugin updates, access to user panel and official support.', 'content-egg' ); ?></p>
<form action="options.php" method="POST">
	<?php settings_fields( $page_slug ); ?>
    <table class="form-table">
		<?php do_settings_fields( $page_slug, 'default' ); ?>
    </table>
	<?php submit_button(); ?>
</form>    

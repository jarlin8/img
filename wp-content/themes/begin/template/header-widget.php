<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('header_widget')) { ?>
	<div class="header-sub">
		<div id="be-menu-widget" class="be-menu-widget be-menu-widget-<?php echo zm_get_option( 'header_widget_f' ); ?>" <?php aos_a(); ?>>
			<?php dynamic_sidebar( 'header-widget' ); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>
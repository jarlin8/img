<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('single_e')) { ?>
<div id="single-widget" class="single-widget-<?php echo zm_get_option( 'single_e_f' ); ?>">
	<div class="single-wt" <?php aos_a(); ?>>
		<?php if ( ! dynamic_sidebar( 'sidebar-e' ) ) : ?>
			<aside class="add-widgets">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“正文底部小工具”添加小工具</a>
			</aside>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>
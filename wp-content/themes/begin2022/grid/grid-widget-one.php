<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('grid_widget_one')) { ?>
<div id="cms-widget-one" class="widget-nav" <?php aos_a(); ?>>
	<?php if ( ! dynamic_sidebar( 'cms-one' ) ) : ?>
		<aside class="add-widgets da bk">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“杂志单栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>
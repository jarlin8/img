<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'grid_widget_one' ) ) { ?>
<div id="cms-widget-one" class="widget-nav betip" <?php aos_a(); ?>>
	<?php if ( ! dynamic_sidebar( 'cms-one' ) ) : ?>
		<aside class="add-widgets">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“杂志单栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<?php be_help( $text = '首页设置 → 分类图片 → 杂志单栏小工具' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
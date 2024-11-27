<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (be_get_option('cms_widget_one')) { ?>
<div id="cms-widget-one" class="widget-nav betip" <?php aos_a(); ?>>
	<?php if ( ! dynamic_sidebar( 'cms-one' ) ) : ?>
		<aside class="add-widgets ms">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志单栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 杂志单栏小工具', $number = 'cms_widget_one_s' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
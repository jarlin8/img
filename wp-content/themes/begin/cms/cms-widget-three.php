<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (be_get_option('cms_widget_three')) { ?>
<div id="cms-widget-three" class="betip" <?php aos_a(); ?>>
	<div class="cmsw cmsw<?php echo be_get_option('cms_widget_three_fl'); ?>">
		<?php if ( ! dynamic_sidebar( 'cms-three' ) ) : ?>
			<aside class="add-widgets ms">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志三栏小工具”添加小工具</a>
			</aside>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 杂志三栏小工具', $number = 'cat_widget_three_s' ); ?>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'grid_widget_two' ) ) { ?>
<div id="cms-widget-three" class="be-aos betip" <?php aos_a(); ?>>
	<div class="cmsw<?php echo be_get_option( 'cms_widget_three_fl' ); ?>">
		<?php if ( ! dynamic_sidebar( 'cms-three' ) ) : ?>
			<aside class="add-widgets">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志三栏小工具”添加小工具</a>
			</aside>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
	<?php be_help( $text = '首页设置 → 分类图片 → 杂志三栏小工具' ); ?>
</div>
<?php } ?>
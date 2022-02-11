<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('grid_widget_two')) { ?>
<div id="cms-widget-three" class="be-aos" <?php aos_a(); ?>>
	<div class="cmsw<?php echo zm_get_option('cms_widget_three_fl'); ?>">
		<?php if ( ! dynamic_sidebar( 'cms-three' ) ) : ?>
			<aside class="add-widgets da bk">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志三栏小工具”添加小工具</a>
			</aside>
		<?php endif; ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
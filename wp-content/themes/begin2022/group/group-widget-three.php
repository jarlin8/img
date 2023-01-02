<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_widget_three')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_widget_three_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div id="group-widget-three" class="group-widget dy">
			<?php if ( ! dynamic_sidebar( 'group-three' ) ) : ?>
				<aside class="add-widgets da bk">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司三栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
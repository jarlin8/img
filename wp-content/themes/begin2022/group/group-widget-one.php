<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_widget_one')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_widget_one_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div id="group-widget-one" class="widget-nav group-widget dy">
			<?php if ( ! dynamic_sidebar( 'group-one' ) ) : ?>
				<aside class="add-widgets da bk">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司一栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
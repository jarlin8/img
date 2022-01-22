<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('footer_w')) { ?>
<?php if (zm_get_option('mobile_footer_w') && wp_is_mobile()) { ?>
<?php } else { ?>
<div id="footer-widget-box" class="footer-site-widget">
	<?php if (zm_get_option('footer_widget_img')) { ?><div class="footer-widget-bg" style="background: url('<?php echo zm_get_option('footer_widget_img'); ?>') no-repeat fixed center / cover;"><?php } ?>
		<div class="footer-widget bgt">
			<?php if ( ! dynamic_sidebar( 'sidebar-f' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“页脚小工具”添加小工具</a>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		</div>
	<?php if (zm_get_option('footer_widget_img')) { ?></div><?php } ?>
<?php } ?>
<?php } ?>
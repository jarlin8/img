<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_two_menu')) { ?>
	<div class="be-menu-widget sort be-menu-widget-cms" <?php aos_a(); ?> name="<?php echo zm_get_option('cms_two_menu_s'); ?>">
	<?php if ( ! dynamic_sidebar( 'cms-two-menu' ) ) : ?>
		<aside class="add-widgets da bk">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志菜单小工具”添加导航菜单小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>
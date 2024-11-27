<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (be_get_option('cms_two_menu')) { ?>
	<div class="be-menu-widget be-menu-widget-cms betip<?php if ( !is_active_sidebar( 'cms-two-menu' ) ) : ?> be-menu-widget-wait<?php endif; ?>" <?php aos_a(); ?>>
	<?php if ( ! dynamic_sidebar( 'cms-two-menu' ) ) : ?>
		<aside class="add-widgets ms">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志菜单小工具”添加导航菜单小工具</a>
		</aside>
	<?php endif; ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 杂志菜单小工具', $number = 'cms_two_menu_s' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
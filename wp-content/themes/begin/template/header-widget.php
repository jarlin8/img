<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'header_widget' ) && is_active_sidebar( 'header-widget' ) ) { ?>
<div class="header-sub">
	<div id="be-menu-widget" class="be-menu-widget be-menu-widget-<?php echo zm_get_option( 'header_widget_f' ); ?>" <?php aos_a(); ?>>
		<?php dynamic_sidebar( 'header-widget' ); ?>
		<?php be_help( $text = '主题选项 → 基本设置 → 侧边栏小工具 → 头部小工具' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
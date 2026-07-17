<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'single_e' ) && is_active_sidebar( 'sidebar-e' ) ) { ?>
<div id="single-widget" class="betip single-widget-<?php echo zm_get_option( 'single_e_f' ); ?>">
	<div class="single-wt" <?php aos_a(); ?>>
		<?php dynamic_sidebar( 'sidebar-e' ); ?>
	</div>
	<?php be_help( $text = '主题选项 → 基本设置 → 侧边栏小工具 → 正文底部小工具' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="sidebar" class="widget-area cms-sidebar">
	<div class="cms-widget" <?php aos_a(); ?>>
		<?php if ( ! dynamic_sidebar( 'cms-s' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="add-widget-tip">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“杂志布局侧边栏”添加小工具</a>
				</div>
			</aside>
		<?php endif; ?>
	</div>
	<?php be_help( $text = '首页设置 → 杂志布局 → 杂志侧边栏' ); ?>
</div>
<div class="clear"></div>
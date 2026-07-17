<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_widget_one' ) ) {
	if ( ! co_get_option( 'widget_one_bg' ) || ( co_get_option( 'widget_one_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'widget_one_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'widget_one_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-widget-one-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div id="group-widget-one" class="widget-nav group-widget dy">
			<?php if ( ! dynamic_sidebar( 'group-one' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司一栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		<?php co_help( $text = '公司主页 → 一栏小工具', $number = 'group_widget_one_s' ); ?>
	</div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_widget_three' ) ) {
	if ( ! be_build( get_the_ID(), 'widget_three_bg' ) || ( be_build( get_the_ID(), 'widget_three_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'widget_three_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'widget_three_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-widget-three-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div id="group-widget-three" class="group-widget dy">
			<?php if ( ! dynamic_sidebar( 'group-three' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司三栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		<?php bu_help( $text = '三栏小工具', $number = 'group_widget_three_s' ); ?>
	</div>
</div>
<?php } ?>
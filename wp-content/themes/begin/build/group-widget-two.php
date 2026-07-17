<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_widget_two' ) ) {
	if ( ! be_build( get_the_ID(), 'widget_two_bg' ) || ( be_build( get_the_ID(), 'widget_two_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'widget_two_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'widget_two_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-widget-two-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div id="group-widget-two" class="group-widget dy">
			<?php if ( ! dynamic_sidebar( 'group-two' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司两栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		<?php bu_help( $text = '两栏小工具', $number = 'group_widget_two_s' ); ?>
	</div>
</div>
<?php } ?>
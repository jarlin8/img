<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_ads' ) ) {
	$items = $args['items'];
	$args = [
		'auto'  => '',
		'white' => ' group-white',
		'gray'  => ' group-gray',
	];
	$bg = '';
	if ( isset( $items['ads_bg'] ) && isset( $args[$items['ads_bg']] ) ) {
		$bg = $args[$items['ads_bg']];
	}
?>
	<div class="g-row g-line group-tgs<?php echo $bg; ?>" <?php aos(); ?>>
		<div class="g-col">
			<div class="reuse-tgs group-reuse-tgs">
				<?php if ( ! empty( $items['group_ads_txt'] ) ) { ?>
					<?php echo $items['group_ads_txt']; ?>
				<?php } ?>
				<?php if ( ! empty( $items['group_ads_visual'] ) ) { ?>
					<?php echo $items['group_ads_visual']; ?>
				<?php } ?>
			</div>
			<?php co_help_n( $text = '广告信息', $items['group_ads_s'] ); ?>
		</div>
	</div>
<?php } ?>
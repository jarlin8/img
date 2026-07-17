<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_coop' ) ) {
	if ( ! co_get_option( 'coop_bg' ) || ( co_get_option( 'coop_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'coop_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'coop_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-tool-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-coop-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option( 'group_coop_t') == '' ) { ?>
					<h3><?php echo co_get_option('group_coop_t'); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option( 'group_coop_des' ) == '' ) { ?>
					<div class="group-des"><?php echo co_get_option( 'group_coop_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-coop-main">
				<?php 
					$coop = ( array ) co_get_option( 'group_coop_item' );
					foreach ( $coop as $items ) {
				?>
					<div class="coop-items coop-<?php echo co_get_option( 'group_coop_f' ); ?>">
						<div class="group-coop-img" data-aos="zoom-in">
							<?php if ( ! empty( $items['group_coop_img'] ) ) { ?>
								<?php if ( ! empty( $items['group_coop_url'] ) ) { ?><a class="group-coop-url" rel="external nofollow" target="_blank" href="<?php echo $items['group_coop_url']; ?>"></a><?php } ?>
								<?php $img = ! empty($items['group_coop_bg']) ? $items['group_coop_bg'] : $items['group_coop_img']; ?>
								<div class="coop40 lazy" style="padding-top: <?php echo co_get_option( 'group_coop_h' ); ?>%;">
									<div class="bgimg" style="background-image: url(<?php echo $img; ?>) !important;">
										<?php if ( ! empty( $items['group_coop_title'] ) && empty( $items['group_coop_title_no'] )) { ?>
											<div class="group-coop-title" data-aos="zoom-in"><?php echo $items['group_coop_title']; ?></div>
										<?php } ?>
									</div>
									<div class="bgimg<?php if ( ! empty( $items['group_coop_gray'] ) ) { ?> gray<?php } ?>" style="background-image: url(<?php echo $items['group_coop_img']; ?>) !important;"></div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php co_help( $text = '公司主页 → 合作', $number = 'coop_s' ); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
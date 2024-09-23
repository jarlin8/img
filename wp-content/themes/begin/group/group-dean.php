<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'dean' ) ) {
	if ( ! co_get_option( 'dean_bg' ) || ( co_get_option( 'dean_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'dean_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'dean_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line deanm-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="deanm">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option( 'dean_t' ) == '' ) { ?>
					<h3><?php echo co_get_option( 'dean_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option( 'dean_des' ) == '' ) { ?>
					<div class="group-des"><?php echo co_get_option( 'dean_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="deanm-main">
				<?php 
					$deanm = ( array ) co_get_option( 'group_dean_item' );
					foreach ( $deanm as $items ) {
				?>
					<div class="deanm deanmove begd edit-buts deanmove-<?php echo co_get_option( 'deanm_f' ); ?><?php if ( co_get_option( 'deanm_fm' ) ) { ?> deanm-jd<?php } else { ?> deanm-fm<?php } ?>">
						<div class="boxs6">
							<div class="deanm-box">

								<div class="de-a" <?php aos_b(); ?>>
									<?php if ( ! empty( $items['group_dean_t1'] ) ) { ?>
										<?php echo $items['group_dean_t1']; ?>
									<?php } ?>
								</div>
						
								<div class="deanquan begd">
									<div class="de-back lazy<?php if ( ! empty( $items['group_dean_title'] ) ) { ?> de-back-fd<?php } ?>">
										<?php if ( ! empty( $items['group_dean_img'] ) ) { ?>
											<div class="thumbs-de-back" style="background-image: url(<?php echo $items['group_dean_img']; ?>);" <?php aos_g(); ?>></div>
										<?php } ?>
										<?php if ( ! empty( $items['group_dean_title'] ) ) { ?>
											<div class="de-b"><?php echo $items['group_dean_title']; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="clear"></div>
								<?php if ( ! empty( $items['group_dean_t2'] ) ) { ?>
									<div class="de-c<?php if ( ! empty( $items['group_dean_l'] ) ) { ?> de-c-l<?php } ?>" <?php aos_b(); ?>><?php echo wpautop( $items['group_dean_t2'] ); ?></div>
								<?php } ?>

								<?php if ( ! empty( $items['group_dean_btn'] ) ) { ?>
									<div class="button-top"></div>
									<div class="de-button" <?php aos_b(); ?>>
										<a class="dah fd" href="<?php echo $items['group_dean_url']; ?>" target="_blank" rel="external nofollow"><?php echo $items['group_dean_btn']; ?></a>
									</div>
									<div class="button-bottom"></div>
								<?php } ?>
								<div class="deanm-bottom"></div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				<?php } ?>
			</div>
		</div>
		<?php co_help( $text = '公司主页 → 服务项目', $number = 'dean_s' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
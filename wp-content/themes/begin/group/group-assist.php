<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_assist' ) ) {
	if ( ! co_get_option( 'assist_bg' ) || ( co_get_option( 'assist_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'assist_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'assist_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-assist-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-assist-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option( 'group_assist_t') == '' ) { ?>
					<h3><?php echo co_get_option( 'group_assist_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_assist_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option( 'group_assist_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-assist-wrap">
				<?php 
					$assist = ( array ) co_get_option( 'group_assist_item' );
					foreach ( $assist as $items ) {
				?>
					<div class="group-assist-main-box">
						<div class="boxs6">
							<div class="group-assist-main">
								<?php if ( ! empty( $items['group_assist_url'] ) ) { ?>
									<a href="<?php echo $items['group_assist_url']; ?>" rel="bookmark" <?php echo goal(); ?>>
								<?php } ?>

								<div class="group-assist" <?php aos_b(); ?>>
									<div class="group-assist-content">
										<h4 class="group-assist-title gat">
											<?php if ( ! empty( $items['group_assist_title'] ) ) { ?>
												<?php echo $items['group_assist_title']; ?>
											<?php } ?>
										</h4>

										<div class="group-assist-des">
											<?php if ( ! empty( $items['group_assist_des'] ) ) { ?>
												<?php echo $items['group_assist_des']; ?>
											<?php } ?>
										</div>

									</div>

									<div class="clear"></div>
								</div>
								<div class="group-assist-ico">
									<?php if ( ! empty( $items['group_assist_ico'] ) ) { ?>
										<i class="<?php echo $items['group_assist_ico']; ?>" style="color:<?php echo $items['group_assist_color']; ?>"></i>
									<?php } ?>
								</div>
								<?php if ( ! empty( $items['group_assist_url'] ) ) { ?></a><?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php co_help( $text = '公司主页 → 支持', $number = 'group_assist_s' ); ?>
	</div>
</div>
<?php } ?>
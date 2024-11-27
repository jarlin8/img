<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'service' ) ) { ?>
<div id="service-bg" class="g-row" style="background: url('<?php echo co_get_option( 'service_bg_img' ); ?>') no-repeat fixed center / cover;" <?php aos(); ?>>
	<div class="service-rgb">
		<div class="g-col">
			<div class="group-service-box">
				<div class="group-title group-service-title" <?php aos_b(); ?>>
						<?php if ( ! co_get_option( 'service_t' ) == '' ) { ?>
							<h3><?php echo co_get_option( 'service_t' ); ?></h3>
						<?php } ?>
						<?php if ( co_get_option( 'service_des' ) ) { ?>
							<div class="group-des"><?php echo co_get_option( 'service_des' ); ?></div>
						<?php } ?>
					<div class="clear"></div>
				</div>

					<?php if ( co_get_option( 'group_service_l' ) ) { ?>
						<div class="group-service group-service-c">
						<?php } else { ?>
						<div class="group-service-centre">
					<?php } ?>
	

					<div class="group-service-des tup">
						<?php if ( ! co_get_option( 'service_c_img' ) == '' ) { ?>
							<img class="be-aos" <?php aos_c(); ?> src="<?php echo co_get_option( 'service_c_img' ); ?>" alt="<?php echo co_get_option( 'service_t' ); ?>">
						<?php } ?>

						<div class="clear"></div>
						<?php if ( co_get_option( 'service_c_txt' ) ) { ?>
							<div class="group-service-content" <?php aos_b(); ?>>
								<?php echo co_get_option( 'service_c_txt' ); ?>
							</div>
						<?php } ?>

						<div class="clear"></div>
					</div>
				</div>

				<div class="group-service group-service-l">
					<div class="service-box">
						<?php 
							$service = ( array ) co_get_option( 'group_service_l' );
							foreach ( $service as $items ) {
						?>
							<?php if ( ! empty( $items['group_service_title_l'] ) ) { ?>
								<div class="p4 tup">
									<div class="p-4" <?php aos_b(); ?>>
											<div class="service-ico">
												<?php if ( ! empty( $items['group_service_ico_l'] ) ) { ?>
													<i class="<?php echo $items['group_service_ico_l']; ?>"></i>
												<?php } ?>
												<?php if ( ! empty( $items['group_service_img_l'] ) ) { ?>
													<figure class="foldimg-bg" style="background-image: url(<?php echo $items['group_service_img_l']; ?>) !important;"></figure>
												<?php } ?>
											</div>

										<div class="p4-title-box">
											<?php if ( ! empty( $items['group_service_title_l'] ) ) { ?>
												<h3 class="p4-title"><?php echo $items['group_service_title_l']; ?></h3>
											<?php } ?>
											<?php if ( ! empty( $items['group_service_txt_l'] ) ) { ?>
												<div class="p4-content">
													<?php echo $items['group_service_txt_l']; ?>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</div>

				<div class="group-service group-service-r">
					<div class="service-box">
						<?php 
							$service = ( array ) co_get_option( 'group_service_r' );
							foreach ( $service as $items ) {
						?>
							<?php if ( ! empty( $items['group_service_title_r'] ) ) { ?>
								<div class="p4 tup">
									<div class="p-4" <?php aos_b(); ?>>

											<div class="service-ico">
												<?php if ( ! empty( $items['group_service_ico_r'] ) ) { ?>
													<i class="<?php echo $items['group_service_ico_r']; ?>"></i>
												<?php } ?>
												<?php if ( ! empty( $items['group_service_img_r'] ) ) { ?>
													<figure class="foldimg-bg" style="background-image: url(<?php echo $items['group_service_img_r']; ?>) !important;"></figure>
												<?php } ?>
											</div>

										<div class="p4-title-box">
											<?php if ( ! empty( $items['group_service_title_r'] ) ) { ?>
												<h3 class="p4-title"><?php echo $items['group_service_title_r']; ?></h3>
											<?php } ?>
											<?php if ( ! empty( $items['group_service_txt_r'] ) ) { ?>
												<div class="p4-content">
													<?php echo $items['group_service_txt_r']; ?>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<?php co_help( $text = '公司主页 → 服务宗旨', $number = 'service_s' ); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
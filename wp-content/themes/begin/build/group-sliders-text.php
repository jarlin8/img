<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_slides_text' ) ) { ?>
	<?php 
		$addslides = ( array ) be_build( get_the_ID(), 'group_slides_text_add' );
		foreach ( $addslides as $items ) {
			$args = [
				'auto'  => '',
				'white' => ' group-white',
				'gray'  => ' group-gray',
			];
			$bg = '';
			if ( isset( $items['slides_text_bg'] ) && isset( $args[$items['slides_text_bg']] ) ) {
				$bg = $args[$items['slides_text_bg']];
			}
	?>

		<div class="g-row g-line group-slider-text group-slider-text-a group-slider-img-text<?php echo $bg; ?>" <?php aos(); ?>>
			<div class="g-col">
				<div class="owl-carousel slides-text<?php if ( ! empty( $items['group_slides_text_r'] ) ) { ?> slider-r<?php } ?><?php if ( ! empty( $items['group_slides_text_per'] ) ) { ?> slider-per<?php } ?>">
					<?php 
						if ( ! empty( $items['group_slides_text_item'] ) ) {
							foreach ( $items['group_slides_text_item'] as $slides ) { ?>

								<div class="slides-item slides-center">
									<div class="slides-content">
										<div class="slides-item-text<?php if ( ! empty( $slides['group_slides_text_ret'] ) ) { ?> ret<?php } ?>">
											<div class="slides-item-title"><?php echo $slides['group_slides_text_title']; ?></div>
											<?php if ( ! empty( $slides['group_slides_text_des'] ) ) { ?>
												<div class="slides-item-des"><?php echo wpautop( $slides['group_slides_text_des'] ); ?></div>
											<?php } ?>

											<?php if ( ! empty( $slides['group_slides_text_btn'] ) ) { ?>
												<div class="slides-item-btn"><a href="<?php echo $slides['group_slides_text_btn_url']; ?>" rel="bookmark" <?php echo goal(); ?> class="slides-button"><?php echo $slides['group_slides_text_btn']; ?></a></div>
											<?php } ?>
										</div>
									</div>

									<?php if ( ! empty( $slides['group_slides_text_img'] ) ) { ?>
										<div class="slides-img">
											<div class="slides-item-img"><img src="<?php echo $slides['group_slides_text_img']; ?>" alt="<?php echo $slides['group_slides_text_title']; ?>"></div>
										</div>
									<?php } ?>
								</div>
							<?php }
						}
					?>
				</div>

				<div class="lazy-img ajax-owl-loading<?php if ( ! empty( $items['group_slides_text_r'] ) ) { ?> slider-r<?php } ?>">
					<?php 
						if ( ! empty( $items['group_slides_text_item'] ) ) {
							foreach ( $items['group_slides_text_item'] as $slides ) { ?>
								<div class="slides-item slides-center">
									<div class="slides-content">
										<div class="slides-item-text<?php if ( ! empty( $slides['group_slides_text_ret'] ) ) { ?> ret<?php } ?>">
											<div class="slides-item-title"><?php echo $slides['group_slides_text_title']; ?></div>
											<?php if ( ! empty( $slides['group_slides_text_des'] ) ) { ?>
												<div class="slides-item-des"><?php echo wpautop( $slides['group_slides_text_des'] ); ?></div>
											<?php } ?>

											<?php if ( ! empty( $slides['group_slides_text_btn'] ) ) { ?>
												<div class="slides-item-btn"><a href="<?php echo $slides['group_slides_text_btn_url']; ?>" rel="bookmark" <?php echo goal(); ?> class="slides-button"><?php echo $slides['group_slides_text_btn']; ?></a></div>
											<?php } ?>
										</div>
									</div>
									<?php if ( ! empty( $slides['group_slides_text_img'] ) ) { ?>
										<div class="slides-img">
											<div class="slides-item-img"><img src="<?php echo $slides['group_slides_text_img']; ?>" alt="<?php echo $slides['group_slides_text_title']; ?>"></div>
										</div>
									<?php } ?>
								</div>
								<?php break; ?>
							<?php }
						}
					?>
				</div>

				<?php bu_help( $text = '图文幻灯', $number = 'group_slides_text_s' ); ?>
			</div>
		</div>
	<?php } ?>
<?php } ?>
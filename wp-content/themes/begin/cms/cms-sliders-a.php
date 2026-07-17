<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_slides_a' ) ) { ?>
	<?php 
		$addslides = ( array ) be_get_option( 'cms_slides_a_add' );
		foreach ( $addslides as $items ) {
	?>

		<div class="cms-slider-text betip ms<?php if ( ! empty( $items['cms_slides_a_r'] ) ) { ?> slider-r<?php } ?>" <?php aos(); ?>>
			<div class="owl-carousel slides-text">
				<?php 
					if ( ! empty( $items['cms_slides_a_item'] ) ) {
						foreach ( $items['cms_slides_a_item'] as $slides ) { ?>

							<div class="slides-item<?php if ( empty( $items['cms_slides_a_back'] ) ) { ?> slides-center<?php } ?>">
								<div class="slides-content">
									<div class="slides-item-text<?php if ( ! empty( $slides['cms_slides_a_ret'] ) ) { ?> ret<?php } ?>">
										<div class="slides-item-title"><?php echo $slides['cms_slides_a_title']; ?></div>
										<?php if ( ! empty( $slides['cms_slides_a_des'] ) ) { ?>
											<div class="slides-item-des"><?php echo wpautop( $slides['cms_slides_a_des'] ); ?></div>
										<?php } ?>

										<?php if ( ! empty( $slides['cms_slides_a_btn'] ) ) { ?>
											<div class="slides-item-btn"><a href="<?php echo $slides['cms_slides_a_btn_url']; ?>" class="slides-button" rel="bookmark" <?php echo goal(); ?>><?php echo $slides['cms_slides_a_btn']; ?></a></div>
										<?php } ?>
									</div>
								</div>

								<?php if ( ! empty( $slides['cms_slides_a_img'] ) ) { ?>
									<div class="slides-img">
										<?php if ( ! empty( $items['cms_slides_a_back'] ) ) { ?>
											<div class="slides-item-img" style="background-image: url(<?php echo $slides['cms_slides_a_img']; ?>);"></div>
										<?php } else { ?>
											<div class="slides-item-img"><img src="<?php echo $slides['cms_slides_a_img']; ?>" alt="<?php echo $slides['cms_slides_a_title']; ?>"></div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php }
					}
				?>
			</div>

			<div class="lazy-img ajax-owl-loading">
				<?php 
					if ( ! empty( $items['cms_slides_a_item'] ) ) {
						foreach ( $items['cms_slides_a_item'] as $slides ) { ?>
							<div class="slides-item<?php if ( empty( $items['cms_slides_a_back'] ) ) { ?> slides-center<?php } ?>">
								<div class="slides-content">
									<div class="slides-item-text<?php if ( ! empty( $slides['cms_slides_a_ret'] ) ) { ?> ret<?php } ?>">
										<div class="slides-item-title"><?php echo $slides['cms_slides_a_title']; ?></div>
										<?php if ( ! empty( $slides['cms_slides_a_des'] ) ) { ?>
											<div class="slides-item-des"><?php echo wpautop( $slides['cms_slides_a_des'] ); ?></div>
										<?php } ?>

										<?php if ( ! empty( $slides['cms_slides_a_btn'] ) ) { ?>
											<div class="slides-item-btn"><a href="<?php echo $slides['cms_slides_a_btn_url']; ?>" class="slides-button" rel="bookmark" <?php echo goal(); ?>><?php echo $slides['cms_slides_a_btn']; ?></a></div>
										<?php } ?>
									</div>
								</div>

								<?php if ( ! empty( $slides['cms_slides_a_img'] ) ) { ?>
									<div class="slides-img">
										<?php if ( ! empty( $items['cms_slides_a_back'] ) ) { ?>
											<div class="slides-item-img" style="background-image: url(<?php echo $slides['cms_slides_a_img']; ?>);"></div>
										<?php } else { ?>
											<div class="slides-item-img"><img src="<?php echo $slides['cms_slides_a_img']; ?>" alt="<?php echo $slides['cms_slides_a_title']; ?>"></div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<?php break; ?>
						<?php }
					}
				?>
			</div>

			<?php cms_help( $text = '首页设置 → 杂志布局 → 图文幻灯', $number = 'cms_sliders_a_s' ); ?>
		</div>
	<?php } ?>
<?php } ?>
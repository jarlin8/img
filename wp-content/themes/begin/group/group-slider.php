<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_slider' ) ) {
	$slider = ( array ) co_get_option( 'slider_group' );
	foreach ( $slider as $items ) {
		$slider_count = count( $slider );
	}
?>
<div class="g-row slider-row">
	<?php if ( ! co_get_option( 'group_slider_video' ) ) { ?>
		<div id="slider-group" class="owl-carousel slider-group">
			<?php 
				$slider = ( array ) co_get_option( 'slider_group' );
				foreach ( $slider as $items ) {
			?>
				<?php if ( ! empty( $items['slider_group_img'] ) ) { ?>
					<div class="slider-group-main">
						<a href="<?php echo $items['slider_group_url']; ?>" rel="bookmark" <?php echo goal(); ?>>
							<div class="group-big-img big-back-img big-ease<?php if ( isset( $items['group_blur'] ) && $items['group_blur'] ) { ?> big-blur<?php } ?>" style="background-image: url('<?php echo $items['slider_group_img']; ?>');height:<?php echo co_get_option('big_back_img_h'); ?>px;"></div>
						</a>

						<div class="slider-group-main-box<?php if ( $items['slider_group_video'] ) { ?> group-main-video<?php } ?><?php if ( $items['slider_group_title_b'] ) { ?> group-main-video-title<?php } ?>">
							<?php if ( $items['slider_group_video'] ) { ?>
								<div class="group-small-video-area group-act2">
									<div class="slider-video-area">
										<video src="<?php echo $items['slider_group_video']; ?>" poster="<?php echo co_get_option( 'group_slider_video_img' ); ?>" autoplay="" loop="" muted="muted" playsinline="" controlslist="nodownload"></video>
									</div>
								</div>

							<?php } else { ?>
								<?php if ( $items['slider_group_small_img'] ) { ?>
									<div class="group-small-img-area group-act2">
										<div class="group-small-img">
											<img src="<?php echo $items['slider_group_small_img']; ?>">
										</div>
									</div>
								<?php } ?>
							<?php } ?>

							<?php if ( $items['slider_group_title_b'] ) { ?>
								<div class="slider-group-mask">
									<div class="group-slider-main<?php if ( $items['slider_group_c'] ) { ?> g-s-c<?php } ?><?php if ( ! $items['slider_group_small_img'] && ! $items['slider_group_video'] ) { ?> g-s-l<?php } ?>">
										<div class="group-slider-content" style="color:<?php if ( isset( $items['group_slider_color'] ) ) { ?><?php echo $items['group_slider_color']; ?><?php } ?>">
											<p class="gt1 s-t-a group-act1"><?php echo $items['slider_group_title_a']; ?></p>
											<p class="gt2 s-t-b group-act2"><?php echo $items['slider_group_title_b']; ?></p>
											<p class="gt1 s-t-c group-act3"><?php echo $items['slider_group_title_c']; ?></p>
										</div>

										<?php if ( $items['slider_group_btu'] ) { ?>
											<div class="group-img-more group-act4"><a href="<?php echo $items['slider_group_btu_url']; ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $items['slider_group_btu']; ?></a></div>
										<?php } ?>

									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>

		<div class="group-lazy-img ajax-owl-loading">
			<?php 
				$slider = ( array ) co_get_option( 'slider_group' );
				foreach ( $slider as $items ) {
			?>
				<?php if ( ! empty( $items['slider_group_img'] ) ) { ?>
					<div class="group-big-img big-back-img<?php if ( isset( $items['group_blur'] ) && $items['group_blur'] ) { ?> big-blur<?php } ?>" style="background-image: url('<?php echo $items['slider_group_img']; ?>');height:<?php echo co_get_option( 'big_back_img_h' ); ?>px;"></div>
				<?php } ?>
				<?php break; ?>
			<?php } ?>
		</div>

	<?php } else { ?>
		<div class="group-slider-video-box slider-videos no-controls-video">
			<video class="slider-video" src="<?php echo co_get_option( 'group_slider_video_url' ); ?>" poster="<?php echo co_get_option( 'group_slider_video_img' ); ?>" autoplay="" loop="" muted="muted" playsinline="" controlslist="nodownload"></video>
			<div class="owl-carousel group-video-text">
				<?php 
					$text = ( array ) co_get_option( 'group_video' );
					foreach ( $text as $items ) {
				?>
					<?php if ( ! empty( $items['group_video_text'] ) ) { ?>
						<div class="group-video-item v-col">
							<div class="group-slider-video-text">
								<?php echo $items['group_video_text']; ?>
							</div>

							<?php if ( ! empty( $items['group_video_btn'] ) ) { ?>
								<div class="group-slider-video-btn">
									<a href="<?php echo $items['group_video_url']; ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $items['group_video_btn']; ?></a>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

	<?php if ( co_get_option( 'group_slide_progress' ) && ! co_get_option( 'group_slider_video' ) && ! co_get_option( 'group_only_img' ) && $slider_count !== 1 ) { ?><div class="slide-mete"><div class="slide-progress" style="-webkit-transition: width <?php echo be_get_option( 'owl_time' ); ?>ms;transition: width <?php echo be_get_option( 'owl_time' ); ?>ms;"></div></div><?php } ?>
	<?php be_help( $text = '公司主页 → 公司幻灯' ); ?>
</div>
<?php } ?>
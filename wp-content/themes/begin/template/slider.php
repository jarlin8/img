<?php 
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'slider' ) ) {
	$slider = ( array ) be_get_option( 'slider_home' );
	foreach ( $slider as $items ) {
		$slider_count = count( $slider );
	}
?>
<div class="slideshow-box<?php if ( be_get_option( 'slide_post' ) ) { ?><?php if ( be_get_option( 'slide_post_m' ) ? ! wp_is_mobile() : true ) { ?> slide-post-box<?php } ?><?php } ?>">
	<div id="slideshow" class="slideshow">

		<?php if ( ! be_get_option( 'show_slider_video' ) ) { ?>
			<?php 
				$slider = ( array ) be_get_option( 'slider_home' );
				echo '<div id="slider-home" class="owl-carousel slider-home slider-current be-wol">';
				foreach ( $slider as $items ) {
					if ( ! empty( $items['slider_home_video'] ) ) {
						$muted = ! $items['slider_home_audio'] ? ' muted' : '';
						$autoplay = ( $slider_count < 2 ) ? ' autoplay' : '';
						echo '<div class="slider-item slider-video-area">';
						echo '<video loop' . $autoplay . ' controls' . $muted . '><source src="' . $items['slider_home_video'] . '" type="video/mp4"></video>';
						echo '</div>';

					} else {
						if ( ! empty( $items['slider_home_img'] ) ) {
							echo '<div class="slider-item">';

							if ( be_get_option('show_img_crop' ) ) {
								echo '<a href="' . $items['slider_home_url'] . '" rel="bookmark" ' . goal() . '><img class="owl-lazy" data-src="' . get_template_directory_uri() . '/prune.php?src=' . $items['slider_home_img'] . '&w=' . be_get_option( 'img_h_w' ) . '&h=' . be_get_option( 'img_h_h' ) . '&a=' . be_get_option( 'crop_top' ) . '&zc=1" alt="' . $items['slider_home_title'] . '"></a>';
							} else {
								echo '<a href="' . $items['slider_home_url'] . '" rel="bookmark" ' . goal() . '><img class="owl-lazy" data-src="' . $items['slider_home_img'] . '" alt="' . $items['slider_home_title'] . '"></a>';
							}

							if ( ! empty( $items['slider_home_title'] ) ) {
								echo '<p class="slider-home-title">' . $items['slider_home_title'] . '</p>';
							}
							echo '</div>';
						}
					}
				}
				echo '</div>';

				echo '<div class="lazy-img ajax-owl-loading">';
					foreach ( $slider as $items ) {
						if ( be_get_option('show_img_crop' ) ) {
							echo '<img src="' . get_template_directory_uri() . '/prune.php?src=' . $items['slider_home_img'] . '&w=' . be_get_option( 'img_h_w' ) . '&h=' . be_get_option( 'img_h_h' ) . '&a=' . be_get_option( 'crop_top' ) . '&zc=1" alt="lazy-img">';
						} else {
							echo '<img src="' . $items['slider_home_img'] . '" alt="lazy-img">';
						}
						break;
					}
				echo '</div>';
			?>

		<?php } else { ?>

			<div class="slider-video-box slider-videos">
				<video class="slider-video" src="<?php echo be_get_option( 'show_slider_video_url' ); ?>" poster="<?php echo be_get_option( 'show_slider_video_img' ); ?>" autoplay="" loop="" muted="muted" playsinline="" controlslist="nodownload"></video>
			</div>
		<?php } ?>

		<?php if ( be_get_option( 'slide_progress') && ! be_get_option( 'show_slider_video' ) && ! be_get_option( 'slider_only_img' ) && $slider_count !== 1 ) { ?><div class="slide-mete"><div class="slide-progress" style="-webkit-transition: width <?php echo be_get_option( 'owl_time' ); ?>ms;transition: width <?php echo be_get_option( 'owl_time' ); ?>ms;"></div></div><?php } ?>
		<div class="clear"></div>
	</div>

	<?php if ( be_get_option( 'slide_post' ) && ( be_get_option( 'slide_post_m' ) ? ! wp_is_mobile() : true ) ) { ?>
		<?php 
			$post = ( array ) be_get_option( 'slider_home_post' );
			echo '<div class="slide-post-main slide-post-' . be_get_option( 'slide_post_n' ). '">';
			echo '<div class="slide-post-item">';
			foreach ( $post as $items ) {
				if ( ! empty( $items['slider_post_img'] ) ) {
					echo '<div class="slide-post">';
					echo '<a rel="bookmark" ' . goal() . ' href="' . $items['slider_post_url'] . '"><img src="' . $items['slider_post_img'] . '" alt="' . $items['slider_post_title'] . '"></a>';
					echo '<a href="' . $items['slider_post_url'] . '" rel="bookmark" ' . goal() . '>';
					if ( ! empty( $items['slider_post_title'] ) ) {
						echo '<div class="slide-post-txt">';
						echo '<h3 class="slide-post-title over">' . $items['slider_post_title'] . '</h3>';
						echo '</div>';
					}
					echo '</a>';
					echo '</div>';
				}
			}
			echo '</div>';
			be_help( $text = '首页设置 → 首页幻灯 → 右侧模块' );
			echo '</div>';
		?>
	<?php } ?>
	<?php be_help( $text = '首页设置→首页幻灯' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php 
// 首页幻灯
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('slider')) { ?>
<div id="slideshow" class="slideshow">
	<?php if (!zm_get_option('slider_only_img')) { ?>
		<?php if (!zm_get_option('show_slider_video')) { ?>
			<div id="slider-home" class="owl-carousel slider-home slider-current be-wol">
				<?php
					$posts = get_posts( array(
						'numberposts' => zm_get_option('slider_n'),
						'post_type' => 'any',
						'meta_key' => 'show',
						'orderby' => 'menu_order', 
						'order' => 'DESC',
						'ignore_sticky_posts' => 1
					) );
				?>

				<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post );$do_not_duplicate[] = $post->ID; $do_show[] = $post->ID; ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
						<?php $image = get_post_meta($post->ID, 'show', true); ?>
						<?php $go_url = get_post_meta($post->ID, 'show_url', true); ?>
						<?php $video = get_post_meta($post->ID, 'slider_video', true); ?>

						<?php if ( get_post_meta($post->ID, 'slider_video', true) ) { ?>
							<a data-fancybox class="slider-video-a" href="<?php echo $video; ?>">
								<div class="slider-video-play slider-video-play-show">
									<div class="slider-video-ico"></div>
								</div>
							</a>
						<?php } ?>

						<?php if (zm_get_option('show_img_crop')) { ?>
							<?php if ( get_post_meta($post->ID, 'show_url', true) ) : ?>
							<a href="<?php echo $go_url; ?>" target="_blank"><img class="owl-lazy" data-src="<?php echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_h_w').'&h='.zm_get_option('img_h_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php the_title(); ?>" /></a>
							<?php else: ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark"><img class="owl-lazy" data-src="<?php echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_h_w').'&h='.zm_get_option('img_h_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php the_title(); ?>" /></a>
							<?php endif; ?>
						<?php } else { ?>
							<?php if ( get_post_meta($post->ID, 'show_url', true) ) : ?>
							<a href="<?php echo $go_url; ?>" target="_blank"><img class="owl-lazy" data-src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
							<?php else: ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark"><img class="owl-lazy" data-src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
							<?php endif; ?>
						<?php } ?>

						<?php if ( get_post_meta($post->ID, 'no_slide_title', true) ) : ?>
						<?php else: ?>
							<?php if ( get_post_meta($post->ID, 'slide_title', true) ) : ?>
							<?php $slide_title = get_post_meta($post->ID, 'slide_title', true); ?>
								<p class="slider-home-title hz slider-home-title-custom"><?php echo $slide_title; ?></p>
							<?php else: ?>
								<p class="slider-home-title hz"><?php the_title(); ?></p>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
			</div>

			<?php
				$posts = get_posts( array(
					'numberposts' => 1,
					'post_type' => 'any',
					'meta_key' => 'show',
					'orderby' => 'menu_order', 
					'order' => 'DESC',
					'ignore_sticky_posts' => 1
				) );
			?>

			<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
				<?php $image = get_post_meta($post->ID, 'show', true); ?>
				<div class="lazy-img ajax-owl-loading">
					<?php if (zm_get_option('show_img_crop')) { ?>
						<img src="<?php echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_h_w').'&h='.zm_get_option('img_h_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" />
					<?php } else { ?>
						<img src="<?php echo $image; ?>" />
					<?php } ?>
				</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>

		<?php } else { ?>
			<div class="slider-video-box">
				<?php echo do_shortcode( '[video mp4 = ' . zm_get_option('show_slider_video_url') . ' loop = "on" autoplay = 1 class = slider-video]' ); ?>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="load slider-play">
			<?php if (zm_get_option('show_slider_video_url')) { ?>
				<a data-fancybox href="<?php echo zm_get_option('show_slider_video_url'); ?>">
			<?php } else { ?>
				<a href="<?php echo zm_get_option('show_slider_img_url'); ?>" rel="external nofollow" >
			<?php } ?>
			<img class="show-slider-img ms bk" src="<?php echo zm_get_option('show_slider_img'); ?>" alt="show">
			<?php if (zm_get_option('show_slider_video_url')) { ?><div class="slider-video-ico"></div><?php } ?></a>
		</div>
	<?php } ?>
	<?php if (zm_get_option('slide_progress') && !zm_get_option('show_slider_video') && !zm_get_option('slider_only_img')) { ?><div class="slide-mete"><div class="slide-progress"></div></div><?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>
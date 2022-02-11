<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_slider')) { ?>
<div class="g-row slider-row">
	<?php if (!zm_get_option('group_only_img')) { ?>
		<?php if (!zm_get_option('group_slider_video')) { ?>
			<div id="slider-group" class="owl-carousel slider-group">
				<?php
					$args = array(
						'posts_per_page' => zm_get_option('group_slider_n'),
						'post_type' => 'page', 
						'meta_key' => 'guide_img',
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'ignore_sticky_posts' => 1
					);
					query_posts($args);
				?>
				<?php while (have_posts()) : the_post(); ?>
				<?php $image = get_post_meta($post->ID, 'guide_img', true); ?>
				<?php $group_slider_url = get_post_meta($post->ID, 'group_slider_url', true); ?>
				<?php $small_img = get_post_meta($post->ID, 'small_img', true); ?>
				<?php $video = get_post_meta($post->ID, 'guide_video', true); ?>
				<?php 
					$s_t_a = get_post_meta($post->ID, 's_t_a', true);
					$s_t_b = get_post_meta($post->ID, 's_t_b', true);
					$s_t_c = get_post_meta($post->ID, 's_t_c', true);
					$s_n_b = get_post_meta($post->ID, 's_n_b', true);
					$s_n_b_l = get_post_meta($post->ID, 's_n_b_l', true);
				?>
					<div class="slider-group-main">
						<?php if ( get_post_meta($post->ID, 'guide_video', true) ) { ?>
							<a data-fancybox class="slider-video-a" href="<?php echo $video; ?>">
								<div class="slider-video-play<?php if ( !get_post_meta($post->ID, 's_t_b', true) ) { ?> slider-video-play-show<?php } ?>">
									<div class="slider-video-ico"></div>
								</div>
							</a>
						<?php } ?>

						<?php if (zm_get_option('group_slider_url')) { ?>
							<a href="<?php if ( get_post_meta($post->ID, 'group_slider_url', true) ) { ?><?php echo $group_slider_url; ?><?php } else { ?><?php the_permalink(); ?><?php } ?>" rel="bookmark"><div class="group-big-img big-back-img<?php if (zm_get_option('group_blur')) { ?> big-blur<?php } ?>" style="background-image: url('<?php echo $image; ?>');height:<?php echo zm_get_option('big_back_img_h'); ?>px;"></div></a>
						<?php } else { ?>
							<div class="group-big-img big-back-img<?php if (zm_get_option('group_blur')) { ?> big-blur<?php } ?>" style="background-image: url('<?php echo $image; ?>');height:<?php echo zm_get_option('big_back_img_h'); ?>px;"></div>
						<?php } ?>

						<?php if (zm_get_option('group_slider_url')) { ?>
							<?php if ( get_post_meta($post->ID, 'small_img', true) ) : ?><div class="group-small-img bgt"><a href="<?php if ( get_post_meta($post->ID, 'group_slider_url', true) ) { ?><?php echo $group_slider_url; ?><?php } else { ?><?php the_permalink(); ?><?php } ?>" rel="bookmark"><img class="group-act2" src="<?php echo $small_img; ?>"></a></div><?php endif; ?>
						<?php } else { ?>
							<?php if ( get_post_meta($post->ID, 'small_img', true) ) : ?><div class="group-small-img bgt"><img class="group-act2" src="<?php echo $small_img; ?>"></div><?php endif; ?>
						<?php } ?>

						<div class="slider-group-mask<?php if ( get_post_meta($post->ID, 's_t_b', true) ) : ?> slider-mask<?php endif; ?>">
							<div class="slider-group-main-box bgt<?php if ( get_post_meta($post->ID, 'small_img', true) ) : ?> small-img-box<?php endif; ?><?php if ( get_post_meta($post->ID, 'g_s_c', true) ) { ?> g-s-c<?php } else { ?> g-s-l<?php } ?>">
								<div class="group-slider-main-body bgt<?php if ( get_post_meta($post->ID, 'guide_video', true) ) { ?> video-main"<?php } ?>">
									<?php if (zm_get_option('group_slider_t')) { ?>
										<?php if ( get_post_meta($post->ID, 's_t_b', true) ) { ?>
											<?php if ( get_post_meta($post->ID, 'small_img', true) ) { ?>
												<div class="group-slider-main bgt">
											<?php } else { ?>
												<div class="group-slider-main no-small bgt">
											<?php } ?>
												<div class="group-slider-content bgt">
													<p class="gt1 s-t-a bgt group-act1"><?php echo $s_t_a; ?></p>
													<p class="gt2 s-t-b bgt group-act2"><?php echo $s_t_b; ?></p>
													<p class="gt1 s-t-c bgt group-act3"><?php echo $s_t_c; ?></p>
												</div>
												<?php if ( get_post_meta($post->ID, 's_n_b', true) ) { ?>
													<div class="group-img-more bgt group-act4"><a class="dah" href="<?php echo $s_n_b_l; ?>" rel="bookmark" target="_blank"><?php echo $s_n_b; ?></a></div>
												<?php } ?>
												<div class="clear"></div>
											</div>
										<?php } ?>
									<?php } ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</div>
			<?php
				$args = array(
					'posts_per_page' => 1,
					'post_type' => 'page', 
					'meta_key' => 'guide_img',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'ignore_sticky_posts' => 1
				);
				query_posts($args);
			?>
			<?php while (have_posts()) : the_post(); ?>
			<?php $image = get_post_meta($post->ID, 'guide_img', true); ?>
			<div class="group-lazy-img ajax-owl-loading" style="height:<?php echo zm_get_option('big_back_img_h'); ?>px;">
				<img src="<?php echo $image; ?>" />
			</div>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
		<?php } else { ?>
			<div class="group-slider-video-box">
				<?php echo do_shortcode( '[video mp4 = ' . zm_get_option('group_slider_video_url') . ' loop = "on" autoplay = 1 class = slider-video]' ); ?>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="load slider-play">
			<?php if (zm_get_option('group_slider_video_url')) { ?>
				<a data-fancybox href="<?php echo zm_get_option('group_slider_video_url'); ?>">
			<?php } else { ?>
				<a href="<?php echo zm_get_option('group_slider_img_url'); ?>" rel="external nofollow" >
			<?php } ?>
			<img class="show-slider-img ms bk" src="<?php echo zm_get_option('group_slider_img'); ?>" alt="show">
			<?php if (zm_get_option('group_slider_video_url')) { ?><div class="slider-video-ico"></div><?php } ?></a>
		</div>

	<?php } ?>
	<?php if (zm_get_option('slide_progress') && !zm_get_option('group_slider_video') && !zm_get_option('group_only_img')) { ?><div class="slide-mete"><div class="slide-progress"></div></div><?php } ?>
</div>
<?php } ?>
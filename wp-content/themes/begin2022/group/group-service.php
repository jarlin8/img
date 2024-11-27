<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('service')) { ?>
<div id="service-bg" class="g-row sort" name="<?php echo zm_get_option('service_s'); ?>" style="background: url('<?php echo zm_get_option('service_bg_img'); ?>') no-repeat fixed center / cover;" <?php aos(); ?>>
	<div class="service-rgb bgt">
		<div class="g-col bgt">
			<div class="group-service-box bgt">
				<div class="group-title group-service-title bgt" <?php aos_b(); ?>>
						<?php if ( zm_get_option('service_t') == '' ) { ?>
						<?php } else { ?>
							<h3 class="bgt"><?php echo zm_get_option('service_t'); ?></h3>
						<?php } ?>
						<?php if ( zm_get_option('service_des') ) { ?><div class="group-des bgt"><?php echo zm_get_option('service_des'); ?></div><?php } ?>
					<div class="clear"></div>
				</div>
				<div class="group-service group-service-c bgt">
					<div class="group-service-des bgt">
						<img class="be-aos" <?php aos_c(); ?> src="<?php echo zm_get_option('service_c_img'); ?>" alt="service" />
						<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_c_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
						<div class="clear"></div>
						<?php if ( zm_get_option('service_c_id') == '' ) { ?>
						<?php } else { ?>
							<div class="group-service-content bgt" <?php aos_b(); ?>>
								<?php 
									$content = get_the_content();
									$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
									echo wp_trim_words( $content, 200, '' );
								?>
							</div>
						<?php } ?>
						<?php endforeach; endif; ?>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
				</div>

				<div class="group-service group-service-l bgt">
					<div class="service-box bgt">
						<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_l_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
						<div class="p4 bgt">
							<div class="p-4 bgt" <?php aos_b(); ?>>
								<figure class="service-thumbnail bk bgt">
									<?php tao_thumbnail(); ?>
								</figure>
								<h3 class="p4-title bgt"><?php echo wp_trim_words( get_the_title(), 120 ); ?>
									<div class="p4-content bgt">
										<?php 
											$content = get_the_content();
											$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
											echo wp_trim_words( $content, 50, '' );
										?>
									</div>
								</h3>
							</div>
						</div>
						<?php endforeach; endif; ?>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
				</div>

				<div class="group-service group-service-r bgt">
					<div class="service-box bgt">
						<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_r_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
						<div class="p4 bgt">
							<div class="p-4 bgt" <?php aos_b(); ?>>
								<figure class="service-thumbnail bk bgt">
									<?php tao_thumbnail(); ?>
								</figure>
								<h3 class="p4-title bgt over"><?php echo wp_trim_words( get_the_title(), 120 ); ?>
									<div class="p4-content bgt">
										<?php 
											$content = get_the_content();
											$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
											echo wp_trim_words( $content, 50, '' );
										?>
									</div>
								</h3>
							</div>
						</div>
						<?php endforeach; endif; ?>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
				</div>

				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
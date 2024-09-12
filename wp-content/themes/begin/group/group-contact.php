<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_contact')) { ?>
	<div class="g-row g-line sort contact" name="<?php echo zm_get_option('group_contact_s'); ?>" <?php aos(); ?>>
		<div class="g-col">
			<div class="section-box group-contact-wrap">
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! zm_get_option('group_contact_t') == '' ) { ?>
						<h3><?php echo zm_get_option('group_contact_t'); ?></h3>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<?php
					$posts = get_posts( array(
						'post_type' => 'any',
						'include' => zm_get_option('contact_p'),
						'ignore_sticky_posts' => 1
					) );
				?>
				<?php if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<div class="group-contact<?php if ( ! zm_get_option( 'contact_img_m' ) || ( zm_get_option( 'contact_img_m' ) == 'contact_img_right')) { ?> group-contact-lr<?php } ?>">
						<div class="single-content<?php if ( zm_get_option( 'tr_contact' ) ) { ?> group-contact-main<?php } else { ?> group-contact-main-all<?php } ?>" <?php aos_b(); ?>>
							<?php 
								$content = get_the_content();
								$content = strip_shortcodes( $content );
								if ( zm_get_option( 'languages_en' ) ) {
									echo begin_strimwidth( strip_tags( $content), 0, zm_get_option('contact_words_n' ), '...' );
								} else {
									echo wp_trim_words( $content, zm_get_option( 'contact_words_n' ), '...' );
								}
							?>
						</div>

						<?php if ( zm_get_option( 'group_contact_bg' ) ) { ?>
							<div class="group-contact-img">
								<img alt="contact" src="<?php echo zm_get_option( 'group_contact_img' ); ?>">
							</div>
						<?php } ?>

						<div class="clear"></div>
						<?php if ( zm_get_option('group_more_z') ||  zm_get_option('group_contact_z')) { ?>
							<div class="group-contact-more">
								<?php if ( zm_get_option('group_more_z')) { ?>
									<span class="group-more" <?php aos_b(); ?>>
										<?php if ( zm_get_option('group_more_url') == '' ) { ?>
											<a class="dah hz" href="<?php the_permalink(); ?>" rel="external nofollow"><?php if ( zm_get_option('group_more_ico') ) { ?><i class="<?php echo zm_get_option('group_more_ico'); ?>"></i><?php } ?><?php echo zm_get_option('group_more_z'); ?></a>
										<?php } else { ?>
											<a class="dah hz" href="<?php echo zm_get_option('group_more_url'); ?>" rel="external nofollow"><?php if ( zm_get_option('group_more_ico') ) { ?><i class="<?php echo zm_get_option('group_more_ico'); ?>"></i><?php } ?><?php echo zm_get_option('group_more_z'); ?></a>
										<?php } ?>
									</span>
								<?php } ?>
								<?php if ( zm_get_option('group_contact_z')) { ?><span class="group-phone" <?php aos_b(); ?>><a class="dah" href="<?php echo  zm_get_option('group_contact_url'); ?>" rel="external nofollow"><?php if ( zm_get_option('group_contact_ico') ) { ?><i class="<?php echo zm_get_option('group_contact_ico'); ?>"></i><?php } ?><?php echo zm_get_option('group_contact_z'); ?></a></span><?php } ?>
								<div class="clear"></div>
							</div>
						<?php } ?>
					</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>
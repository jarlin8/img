<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_explain' ) ) { ?>
<div class="explain g-line sort" name="<?php echo zm_get_option( 'group_explain_s' ); ?>" <?php aos(); ?>>
	<div class="g-row">
		<div class="g-col">
			<div class="section-box group-explain-wrap">
				<?php
					$args = array(
						'post_type' => 'page', 
						'p'         => zm_get_option( 'explain_p' ),
					);
					query_posts($args);
				?>
				<?php while (have_posts()) : the_post(); ?>
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! zm_get_option( 'group_explain_t' ) == '' ) { ?>
						<h3><?php echo zm_get_option('group_explain_t'); ?></h3>
					<?php } ?>
					<?php if ( ! zm_get_option( 'group_explain_des' ) == '' ) { ?>
						<div class="group-des"><?php echo zm_get_option( 'group_explain_des' ); ?></div>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="group-explain-box">
					<div class="group-explain-img-box<?php if ( ! zm_get_option( 'ex_thumbnail_only' ) ) { ?> explain-img<?php } ?>" <?php aos_g(); ?>>
						<figure class="group-explain-img">
							<?php 
								if ( zm_get_option( 'lazy_s' ) ) {
									echo '<span class="load"><a rel="external nofollow" href="' . zm_get_option( 'group_explain_url') . '"><img src="'. get_template_directory_uri() . '/img/loading.png" data-original="' . zm_get_option( 'ex_thumbnail_a' ) . '" alt="' . $post->post_title  . '" /></a></span>';
								} else {
									echo '<a rel="external nofollow" href="' . zm_get_option('group_explain_url') . '"><img src="' . zm_get_option( 'ex_thumbnail_a' ) . '" alt="' . $post->post_title  . '" /></a>';
								}
							?>
						</figure>
						<?php if ( ! zm_get_option( 'ex_thumbnail_only' ) ) { ?>
							<figure class="group-explain-img">
								<?php 
									if ( zm_get_option( 'lazy_s' ) ) {
										echo '<span class="load"><a rel="external nofollow" href="' . zm_get_option( 'group_explain_url' ) . '"><img src="'. get_template_directory_uri() . '/img/loading.png" data-original="' . zm_get_option( 'ex_thumbnail_b' ) . '" alt="' . $post->post_title  . '" /></a></span>';
									} else {
										echo '<a rel="external nofollow" href="' . zm_get_option( 'group_explain_url' ) . '"><img src="' . zm_get_option( 'ex_thumbnail_b' ) . '" alt="' . $post->post_title  . '" /></a>';
									}
								?>
							</figure>
						<?php } ?>
					</div>

					<div class="group-explain">
						<div class="group-explain-main edit-buts single-content" <?php aos_b(); ?>>
							<?php if ( ! zm_get_option( 'explain_content_t' ) == '' ) { ?>
								<a href="<?php echo zm_get_option( 'group_explain_url' ); ?>" rel="external nofollow">
									<div class="group-explain-t">
										<?php if ( ! zm_get_option( 'explain_content_t' ) == '' ) { ?>
											<span class="explain-content-logo"><img src="<?php echo zm_get_option( 'logo_small_b' ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></span>
										<?php } ?>
										<?php echo zm_get_option( 'explain_content_t' ); ?>
									</div>
								</a>
							<?php } ?>
							<?php 
								$content = get_the_content();
								$content = strip_shortcodes( $content );
								if ( zm_get_option( 'languages_en' ) ) {
									echo begin_strimwidth( strip_tags( $content ), 0, zm_get_option( 'explain_words_n' ), '...' );
								} else {
									echo wp_trim_words( $content, zm_get_option( 'explain_words_n' ), '...' );
								}
							?>
							<?php edit_post_link( '<i class="be be-editor"></i>', '<span class="edit-link-but">', '</span>' ); ?>
						</div>
						<div class="clear"></div>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
			<?php if ( zm_get_option( 'group_explain_url' ) && ! zm_get_option( 'group_explain_more_no' ) ) { ?>
				<div class="group-post-more bk da">
					<a href="<?php echo zm_get_option( 'group_explain_url' ); ?>" title="<?php _e( '详细查看', 'begin' ); ?>" rel="external nofollow">
						<?php if ( zm_get_option( 'group_explain_more' ) ) { ?>
							<?php echo zm_get_option( 'group_explain_more' ); ?>
						<?php } else { ?>
							<i class="be be-more"></i>
						<?php } ?>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
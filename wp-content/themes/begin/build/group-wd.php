<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_wd' ) ) {
	if ( ! be_build( get_the_ID(), 'wd_bg' ) || ( be_build( get_the_ID(), 'wd_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'wd_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'wd_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php $display_categories =  explode( ',', be_build( get_the_ID(), 'group_wd_id' ) ); foreach ( $display_categories as $category ) {
		$cat = be_build( get_the_ID(), 'group_no_cat_child' ) ? 'category' : 'category__in';
	?>
		<div class="g-row grl-img g-line<?php echo $bg; ?>" <?php aos(); ?>>
			<div class="g-col">
				<div class="gr-wd-box">
					<div class="gr-wd-b">
						<div class="gr-wd gr-wd-img">
							<?php
								$wdtop = [];
								$wdimg = get_posts( array(
									'meta_key'       => 'cat_top',
									'posts_per_page' => 1,
									'post_status'    => 'publish',
									$cat             => $category,
								) );
							?>
							<?php foreach ( $wdimg as $post ) : setup_postdata( $post ); $wdtop[] = $post->ID; ?>
								<div class="group-top-img" <?php aos_b(); ?>>
									<?php echo gr_wd_thumbnail(); ?>
									<div class="gr-cat-wd">
										<div class="gr-cat-wd-title"><a href="<?php echo get_category_link( $category ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo get_cat_name( $category ); ?></a></div>
										<div class="clear"></div>
									</div>
								</div>
							<?php endforeach; ?>
							<?php wp_reset_postdata(); ?>
						</div>

						<div class="gr-wd gr-wd-w" <?php aos_f(); ?>>
							<?php
								$excerpt = get_posts( array(
									'posts_per_page' => 1,
									'post_status'    => 'publish',
									$cat             => $category,
									'post__not_in'   => isset( $wdtop ) ? $wdtop : []
								) );
							?>
							<?php foreach ( $excerpt as $post ) : setup_postdata( $post ); $wdtop[] = $post->ID; ?>
								<?php the_title( sprintf( '<h2 class="gr-title gr-wd-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<p class="be-aos" <?php aos_f(); ?>>
									<?php if (has_excerpt('')){
											echo wp_trim_words( get_the_excerpt(), 92, '...' );
										} else {
											$content = get_the_content();
											$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
											echo wp_trim_words( $content, 95, '...' );
									    }
									?>
								</p>

							<?php endforeach; ?>
							<?php wp_reset_postdata(); ?>

							<ul <?php aos_f(); ?>>
								<?php
									$posts = get_posts( array(
										'posts_per_page' => 5,
										'post_status'    => 'publish',
										$cat             => $category,
										'offset'         => 1,
										'post__not_in'   => isset( $wdtop ) ? $wdtop : [],
									) );
								?>
								<?php foreach ( $posts as $post ) : setup_postdata( $post ); $wdtop[] = $post->ID; ?>
									<?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</ul>

						</div>
						<div class="clear"></div>
					</div>
				</div>
				<?php bu_help( $text = '分类左右图', $number = 'group_wd_s' ); ?>
				<div class="clear"></div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
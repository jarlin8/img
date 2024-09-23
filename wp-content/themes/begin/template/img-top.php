<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="grid-cat-box grid-cat-top" <?php aos_a(); ?>>
	<?php
		$args = array(
			'post__in'  => explode( ',', be_get_option( 'img_top_id' ) ),
			'post_type' => 'any',
			'orderby'   => 'post__in', 
			'order'     => 'DESC',
			'ignore_sticky_posts' => true, 
			);
		$query = new WP_Query( $args );
	?>
	<?php if ( $query->have_posts() ) : ?>
		<div class="marked-ico" <?php aos_a(); ?>><?php _e( '推荐', 'begin' ); ?></div>
	<?php endif; ?>
	<div class="grid-cat-area grid-cat-<?php echo be_get_option('img_top_f'); ?>">
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post" <?php aos_a(); ?>>
				<div class="boxs1">
					<div class="grid-cat-bx4 grid-cat-new ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
						<figure class="picture-img">
							<?php echo zm_thumbnail(); ?>
							<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
						</figure>

						<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

						<span class="grid-inf">
							<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
								<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
								<span class="link-inf"><?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true);{ echo $link_inf;}?></span>
								<span class="grid-inf-l">
								<?php views_span(); ?>
								<?php if ( get_post_meta(get_the_ID(), 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta(get_the_ID(), 'mark', true);{ echo $mark;}; ?></span><?php } ?>
								</span>
							<?php } else { ?>
								<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
								<?php views_span(); ?>
								<span class="grid-inf-l">
									<?php grid_meta(); ?>
									<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
									<?php if ( get_post_meta(get_the_ID(), 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta(get_the_ID(), 'mark', true);{ echo $mark;}; ?></span><?php } ?>
								</span>
							<?php } ?>
			 			</span>

			 			<div class="clear"></div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
	<?php be_help( $text = '首页设置 → 图片布局 → 其它模块 → 推荐文章' ); ?>
	<div class="clear"></div>
</div>
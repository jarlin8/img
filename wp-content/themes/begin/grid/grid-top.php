<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="grid-cat-box grid-cat-top" <?php aos_a(); ?>>
	<div class="grid-cat-site grid-cat-<?php echo zm_get_option('img_top_f'); ?>">
		<?php query_posts( array ( 'meta_key' => 'cms_top', 'showposts' => zm_get_option('cms_top_n'), 'post__not_in' => get_option( 'sticky_posts') ) ); while ( have_posts() ) : the_post(); $do_not_duplicate[] = $post->ID; $do_not_top[] = $post->ID; ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('bky'); ?>>
				<div class="grid-cat-bx4 sup ms bk">
					<figure class="picture-img">
						<?php zm_thumbnail(); ?>
						<?php if ( has_post_format('video') ) { ?><div class="play-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>

					<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

					<span class="grid-inf">
						<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
							<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
							<span class="link-inf"><?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true);{ echo $link_inf;}?></span>
							<span class="grid-inf-l">
							<?php views_span(); ?>
							<?php echo t_mark(); ?>
							</span>
						<?php } else { ?>
							<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
							<?php views_span(); ?>
							<span class="grid-inf-l">
								<?php echo be_vip_meta(); ?>
								<?php grid_meta(); ?>
								<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
								<?php echo t_mark(); ?>
							</span>
						<?php } ?>
		 			</span>

		 			<div class="clear"></div>
				</div>
			</article>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
	<div class="clear"></div>
</div>
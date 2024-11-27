<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片布局，有播放图标
 */
get_header(); ?>

	<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
		<main id="main" class="be-main site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_a(); ?>>
				<div class="boxs1">
					<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
						<figure class="picture-img">
							<?php echo zm_grid_thumbnail(); ?>
							<i class="be be-play"></i>
						</figure>
						<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<span class="grid-inf">
							<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
							<span class="g-cat"><?php zm_category(); ?></span>
							<span class="grid-inf-l">
								<?php echo be_vip_meta(); ?>
								<?php views_span(); ?>
								<span class="date"><?php the_time( 'm/d' ); ?></span>
								<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							</span>
			 			</span>
			 			<div class="clear"></div>
					</div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
		<div class="clear"></div>
	</section><!-- .content-area -->
<?php get_footer(); ?>
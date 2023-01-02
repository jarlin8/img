<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片布局，有播放图标
 */
get_header(); ?>

	<section id="picture" class="picture-area content-area grid-cat-<?php echo zm_get_option('img_f'); ?>">
		<main id="main" class="be-main site-main" role="main">
		<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
			?>
		<?php } ?>

		<?php if ( zm_get_option( 'order_btu' ) ) { ?><?php be_order(); ?><?php } ?>

			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('picture scl'); ?>>
				<div class="picture-box sup ms bk">
					<figure class="picture-img">
						<?php zm_grid_thumbnail(); ?>
						<a rel="external nofollow" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<span class="grid-inf">
						<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
						<span class="g-cat"><?php zm_category(); ?></span>
						<span class="grid-inf-l">
							<span class="date"><?php the_time( 'm/d' ); ?></span>
							<?php views_span(); ?>
							<?php if ( get_post_meta(get_the_ID(), 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							<?php echo be_vip_meta(); ?>
						</span>
		 			</span>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
		<div class="clear"></div>
	</section><!-- .content-area -->
<?php get_footer(); ?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片布局
 */
get_header(); ?>

	<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
		<main id="main" class="be-main site-main" role="main">
			<?php be_exclude_child_cats(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_a(); ?>>
				<div class="boxs1">
					<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
						<figure class="picture-img">
							<?php echo be_img_excerpt(); ?>
							<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
								<?php echo zm_thumbnail_link(); ?>
							<?php } else { ?>
								<?php echo zm_thumbnail(); ?>
							<?php } ?>

							<?php if ( has_post_format('video') ) { ?><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
							<?php if ( has_post_format('link') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-link"></i></a></div><?php } ?>
						</figure>

						<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
							<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
							<h2 class="grid-title"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
						<?php } else { ?>
							<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php } ?>
						<?php grid_inf(); ?>
			 			<div class="clear"></div>
					</div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<div><?php begin_pagenav(); ?></div>
		<div class="clear"></div>
	</section><!-- .content-area -->
<?php get_footer(); ?>
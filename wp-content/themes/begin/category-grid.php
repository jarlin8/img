<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片布局，单独设置大小
 */
get_header(); ?>

	<section id="picture" class="picture-area content-area grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
		<main id="main" class="be-main site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post wg scl" <?php aos_a(); ?>>
				<div class="boxs1">
					<div class="picture-box ms<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
						<figure class="picture-img">
							<?php echo be_img_excerpt(); ?>
							<?php echo zm_grid_thumbnail(); ?>
							<?php if ( has_post_format('video') ) { ?><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
						</figure>
						<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php grid_inf(); ?>
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
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 图片布局，单独设置大小
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
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('wg scl'); ?>>
				<div class="picture-box sup ms bk">
					<figure class="picture-img">
						<?php echo be_img_excerpt(); ?>
						<?php zm_grid_thumbnail(); ?>
						<?php if ( has_post_format('video') ) { ?><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<?php grid_inf(); ?>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
		<div class="clear"></div>
	</section><!-- .content-area -->
<?php get_footer(); ?>
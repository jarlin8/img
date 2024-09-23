<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php blog_template(); ?>
	<?php if ( be_get_option( 'order_btu' ) && ! be_get_option( 'blog_ajax' ) && ! is_paged() ) { ?><?php be_order_btu(); ?><?php } ?>
	<div class="blog-main betip">
		<?php if ( be_get_option( 'blog_ajax' ) ) { ?>

			<?php 
				$cat_ids  = be_get_option( 'blog_ajax_id' ) ? implode( ',', be_get_option('blog_ajax_id' ) ) : '';
				$children = ( be_get_option( 'blog_ajax_cat_chil' ) == 'false' ) ? 'false' : 'true';
				echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" posts_per_page="' . be_get_option( 'blog_ajax_n' ) . '" style="' . be_get_option( 'blog_ajax_cat_style' ) . '" btn="' . be_get_option( 'blog_ajax_cat_btn' ) . '" more="' . be_get_option( 'blog_ajax_nav_btn' ) . '" infinite="' . be_get_option( 'blog_ajax_infinite' ) . '" children="' . $children . '" column="2" sticky="0"]' );
			?>

			<?php be_help( $text = '首页设置 → 博客布局 → Ajax模式' ); ?>

		<?php } else { ?>
			<?php
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				$notcat = be_get_option( 'blog_not_cat' ) ? implode( ',', be_get_option( 'blog_not_cat' ) ) : '';
				$top_id = be_get_option( 'blog_top' ) ? explode( ',', be_get_option( 'blog_top_id' ) ) : '';

				$args = array(
					'category__not_in'    => explode( ',', $notcat ),
					'post__not_in'        => $top_id,
					'ignore_sticky_posts' => 0, 
					'paged'               => $paged
				);
				query_posts( $args );
			?>

			<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php get_template_part('ad/ads', 'archive'); ?>

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>
			<?php endif; ?>

		<?php } ?>

	</div>
</main>

<?php if ( ! be_get_option( 'blog_ajax' ) ) { ?><?php begin_pagenav(); ?><?php } ?>

</div>
<?php if ( ! be_get_option( 'blog_no_sidebar' ) ) { ?>
<?php blog_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
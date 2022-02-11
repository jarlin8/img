<?php
/*
Template Name: 博客页面
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>
<?php blog_template(); ?>
	<div class="blog-main">
		<?php
			if(is_front_page()){
				$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
			}else{
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			}

			$cms_top = 'cms_top';
			$compare = 'NOT EXISTS';
			$notcat = explode(',',zm_get_option('not_cat_n'));
			$args = array(
				'category__not_in' => $notcat,
			    'ignore_sticky_posts' => 0, 
				'paged' => $paged,
				'meta_query' => array(
					array(
						'key' => $cms_top,
						'compare' => $compare
					)
				)
			);
			query_posts( $args );
		?>
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', get_post_format() ); ?>

			<?php get_template_part('ad/ads', 'archive'); ?>

			<?php if (!zm_get_option('blog_ajax_tabs')) { ?>
				<?php if ($wp_query->current_post == zm_get_option('blog_ajax_tabs_n') && !is_paged()) { ?>
					<?php if (zm_get_option('post_no_margin')) { ?>
						<div class="tab-no-margin">
					<?php } else { ?>
						<div class="tab-margin">
					<?php } ?>
					<?php get_template_part( '/template/cat-tab' ); ?>
					</div>
				<?php } ?>
			<?php } ?>

		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'template/content', 'none' ); ?>
		<?php endif; ?>

		<?php if ( !is_paged() && !zm_get_option('new_cat_id')== '' ) { ?></div><?php } ?>
	</div>
</main>

	<?php begin_pagenav(); ?>
	<?php wp_reset_query(); ?>
</div>
<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>
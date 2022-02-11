<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_top')) { ?>
	<?php 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$do_show[] = '';
		$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'category__not_in' => explode(',', zm_get_option('not_news_n')), 'post__not_in' => $do_show, 'paged' => $paged, 'meta_query' => array( array( 'key' => 'cms_top', 'compare' => 'NOT EXISTS'))));
	?>
<?php } else { ?>
	<?php 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$do_show[] = '';
		$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'post__not_in' => $do_show, 'paged' => $paged, 'category__not_in' => explode(',',zm_get_option('not_news_n'))) ); 
	?>
<?php } ?>
<div class="cms-news-normal-box<?php if (zm_get_option('post_no_margin')) { ?> cms-news-normal<?php } ?>">
	<?php global $count; while($recent->have_posts()) : $recent->the_post(); $count++; $do_not_duplicate[] = $post->ID; ?>
		<?php get_template_part( 'template/content', get_post_format() ); ?>
		<?php if ($count == 1) : ?>
			<?php if (zm_get_option('post_img')) { ?>
				<div class="line-four" <?php aos_a(); ?>>
					<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
				</div>
			<?php } ?>
		<?php endif; ?>
		<?php if ($count == 2) : ?>
			<?php get_template_part('ad/ads', 'cms'); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</div>
<?php if (zm_get_option('post_no_margin') || zm_get_option('news_model')) { ?><div class="domargin"></div><?php } ?>
<div class="clear"></div>
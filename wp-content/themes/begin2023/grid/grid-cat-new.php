<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('grid_cat_new')) { ?>
<div class="grid-cat-box" <?php aos_a(); ?>>

	<div class="grid-cat-new-box">
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_b(); ?>><?php _e( '最近更新', 'begin' ); ?></h3>
		</div>
		<div class="clear"></div>

		<div class="ajax-cat-cntent-grid cat-border catpast grid-cat-site grid-cat-<?php echo zm_get_option('grid_new_f'); ?>"></div>
		<div class="ajax-new-cntent-grid netcurrent">
			<div class="grid-cat-site grid-cat-<?php echo zm_get_option('grid_new_f'); ?>">
				<?php if (zm_get_option('cms_top')) { ?>
					<?php 
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$do_show[] = '';
						$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('grid_cat_news_n'), 'category__not_in' => explode(',', zm_get_option('not_news_n')), 'post__not_in' => $do_show, 'paged' => $paged, 'meta_query' => array( array( 'key' => 'cms_top', 'compare' => 'NOT EXISTS'))));
					?>
				<?php } else { ?>
					<?php 
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$do_show[] = '';
						$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('grid_cat_news_n'), 'post__not_in' => $do_show, 'paged' => $paged, 'category__not_in' => explode(',',zm_get_option('not_news_n'))) ); 
					?>
				<?php } ?>
				<?php while($recent->have_posts()) : $recent->the_post();$count = '';$count++; $do_not_duplicate[] = $post->ID; ?>
				<?php grid_new(); ?>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
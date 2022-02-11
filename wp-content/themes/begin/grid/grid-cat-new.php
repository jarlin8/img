<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('grid_cat_new')) { ?>
<div class="grid-cat-box" <?php aos_a(); ?>>

	<div class="grid-cat-new-box">
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_b(); ?>><?php _e( '最近更新', 'begin' ); ?></h3>
			<?php if (zm_get_option('grid_new_cat_id')) { ?>
				<div class="be-new-nav-grid" <?php aos_a(); ?>>
					<ul class="new-tabs-cat-grid">
						<li class="new-tabs-all-grid hz current"><?php _e( '最新文章', 'begin' ); ?></li>
						<?php $display_categories = explode(',',zm_get_option('grid_new_cat_id') ); foreach ($display_categories as $category) { ?>
							<?php query_posts( array( 'cat' => $category) ); ?>
							<li><a class="tags-cat-grid" data-id="<?php echo $category; ?>" href="#"><?php single_cat_title(); ?></a></li>
						<?php wp_reset_query(); ?>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<div class="clear"></div>

		<div class="ajax-cat-cntent-grid cat-border catpast"></div>
		<div class="ajax-new-cntent-grid netcurrent">
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
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
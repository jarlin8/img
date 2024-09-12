<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_cat_tab')) { ?>
<div class="cms-cat-tab-box sort" name="<?php echo zm_get_option('cms_cat_tab_s'); ?>" <?php aos_a(); ?>>
	<div class="cms-cat-tab">
		<div class="cms-tab-nav bk ms">
			<?php query_posts( array( 'cat' => zm_get_option('cms_cat_tab_one_id') )); ?>
			<ul class="cms-tabs-all tab-but current"><li><?php single_cat_title(); ?></li></ul>
			<?php wp_reset_query(); ?>
			<ul class="cms-tabs-cat">
				<?php $display_categories = explode(',',zm_get_option('cms_cat_tab_id') ); foreach ($display_categories as $category) { ?>
					<?php query_posts( array( 'cat' => $category) ); ?>
					<li><a class="cms-tags-cat tab-but" data-id="<?php echo $category; ?>" href="#"><?php single_cat_title(); ?></a></li>
				<?php wp_reset_query(); ?>
				<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="cms-ajax-cat-cntent cms-ajax-area lic bk ms cat-border catpast"></div>
		<div class="cms-ajax-cntent cms-ajax-area bk bkyt lic ms netcurrent">
			<?php if (zm_get_option('cms_cat_tab_img')) { ?>
				<div class="cat-tab-img-box">
					<?php query_posts( array( 'showposts' => 4, 'cat' => zm_get_option('cms_cat_tab_one_id'), 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="cat-tab-img" <?php aos_a(); ?>>
							<div class="cat-tab-img-x4">
								<figure class="tab-thumbnail">
									<?php zm_thumbnail(); ?>
								</figure>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			<?php } ?>
			<div class="clear"></div>
			<ul class="cat-site" data-aos="fade-in">
				<?php query_posts( array( 'showposts' => zm_get_option('cms_cat_tab_n'), 'cat' => zm_get_option('cms_cat_tab_one_id'), 'post__not_in' => $do_not_duplicate ) ); ?>
				<?php $s=0; ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php $s++; ?>
					<?php the_title( sprintf( '<li class="list-title-date high-'. mt_rand(1, $s) .'"><a class="srm" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
				<?php $cat_id = zm_get_option('cms_cat_tab_one_id'); ?>
				<div class="grid-cat-more"><a href="<?php echo get_category_link($cat_id); ?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a></div>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>
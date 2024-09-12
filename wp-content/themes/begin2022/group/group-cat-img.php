<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_img')) { ?>
<?php $display_categories =  explode(',',zm_get_option('group_img_id') ); foreach ($display_categories as $category) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_img_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-features">

				<?php query_posts( array( 'showposts' => zm_get_option('group_img_id'), 'cat' => $category ) ); ?>
				<div class="group-title" <?php aos_b(); ?>>
					<h3><?php single_cat_title(); ?></h3>
					<?php echo the_archive_description( '<div class="group-des">', '</div>' ); ?>
					<div class="clear"></div>
				</div>

				<div class="section-box" <?php aos_b(); ?>>
					<?php query_posts( array( 'showposts' => zm_get_option('group_img_n'), 'cat' => $category ) ); ?>
						<?php while ( have_posts() ) : the_post(); ?>
					<div class="g4 g<?php echo zm_get_option('group_img_f'); ?>">
						<div class="box-4">
							<figure class="section-thumbnail">
								<?php tao_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
						</div>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
					<div class="group-cat-img-more"><a href="<?php echo get_category_link($category);?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a></div>
				</div>

		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
<?php } ?>
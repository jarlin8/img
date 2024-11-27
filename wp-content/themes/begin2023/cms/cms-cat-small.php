<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cat_small')) { ?>
<div class="line-small sort" name="<?php echo zm_get_option('cat_small_s'); ?>">
	<?php $display_categories = explode(',',zm_get_option('cat_small_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => 1, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

	<div class="xl2 xm2">
		<div class="cat-container ms bk<?php if ( zm_get_option( 'cat_small_z' ) ) { ?> cms-cat-txt<?php } ?>" <?php aos_a(); ?>>
			<h3 class="cat-title bkx da">
				<a href="<?php echo get_category_link($category);?>">
					<?php cat_module_title(); ?>
				</a>
			</h3>
			<div class="clear"></div>
			<div class="cat-site">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if ( zm_get_option( 'cat_small_img_no' ) ) { ?>
						<ul class="cat-small-list"><?php list_date(); ?><?php the_title( sprintf( '<li class="list-title' . date_class() . '"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' ); ?></ul>
					<?php } else { ?>
						<?php if (zm_get_option('cat_small_z')) { ?>
								<figure class="small-thumbnail"><?php zm_long_thumbnail(); ?></figure>
								<?php the_title( sprintf( '<h2 class="entry-small-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<?php } else { ?>
								<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="cat-img-small">
									<figure class="thumbnail"><?php zm_thumbnail(); ?></figure>
									<div class="cat-main">
										<?php if (has_excerpt('')){
												echo wp_trim_words( get_the_excerpt(), 45, '...' );
											} else {
												$content = get_the_content();
												$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
												echo wp_trim_words( $content, 42, '...' );
									        }
										?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					<?php endwhile; ?>
				<div class="clear"></div>
				<ul class="cat-list">
					<?php if (zm_get_option('no_cat_child')) { ?>
						<?php query_posts( array( 'showposts' => zm_get_option('cat_small_n'), 'cat' => $category, 'offset' => 1, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } else { ?>
						<?php query_posts( array( 'showposts' => zm_get_option('cat_small_n'), 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<?php list_date(); ?>
						<?php the_title( sprintf( '<li class="list-title' . date_class() . '"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' ); ?>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>
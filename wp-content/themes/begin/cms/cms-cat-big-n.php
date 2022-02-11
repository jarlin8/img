<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cat_big_not')) { ?>
<div class="line-big sort" name="<?php echo zm_get_option('cat_big_not_s'); ?>">
	<?php $display_categories = explode(',',zm_get_option('cat_big_not_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_big_not_n'), 'cat' => $category, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_big_not_n'), 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

	<?php if (zm_get_option('cat_big_not_three')) { ?>
	<div class="cl3">
	<?php } else { ?>
	<div class="xl3 xm3">
	<?php } ?>
		<div class="cat-container ms bk" <?php aos_a(); ?>>
			<h3 class="cat-title bkx"><a href="<?php echo get_category_link($category);?>"><?php cat_module_title(); ?></a></h3>
			<div class="clear"></div>
			<div class="cat-site">
				<ul class="cat-list">
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
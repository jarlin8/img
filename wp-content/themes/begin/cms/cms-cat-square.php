<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cat_square')) { ?>
<div class="cms-cat-square sort" name="<?php echo zm_get_option('cat_square_s'); ?>" <?php aos_a(); ?>>
	<div class="cms-cat-main ms bk">
		<?php $display_categories = explode(',',zm_get_option('cat_square_id') ); foreach ($display_categories as $category) { ?>
		<?php if (zm_get_option('no_cat_child')) { ?>
			<?php query_posts( array('cat' => $category ) ); ?>
			<?php query_posts( array( 'showposts' => zm_get_option('cat_square_n'), 'category__in' => array(get_query_var('cat')), 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
		<?php } else { ?>
			<?php query_posts( array( 'showposts' => zm_get_option('cat_square_n'), 'cat' => $category, 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
		<?php } ?>

		<h3 class="cat-square-title bkx">
			<a href="<?php echo get_category_link($category);?>">
				<?php cat_module_title(); ?>
			</a>
		</h3>
		<div class="clear"></div>
		<div class="cat-g5">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('gls'); ?>>
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
					</figure>
					<header class="entry-header entry-header-square">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>
				</article>
				<?php endwhile; ?>
				<div class="clear"></div>
				<?php wp_reset_query(); ?>
			</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
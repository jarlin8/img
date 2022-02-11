<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('grid_carousel')) { ?>
<div class="grid-carousel-box" <?php aos_a(); ?>>
	<?php
		$cat = get_category(zm_get_option('grid_carousel_id'));
		$cat_links=get_category_link($cat->term_id); 
		$args=array( 'include' => zm_get_option('grid_carousel_id') );
		$cats = get_categories($args);
		foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
	?>
	<div class="grid-cat-title-box">
		<h3 class="grid-cat-title" <?php aos_a(); ?>><a href="<?php echo $cat_links; ?>" title="<?php _e( '更多', 'begin' ); ?>"><?php single_cat_title(); ?></a></h3>
		<div class="grid-cat-des" <?php aos_a(); ?>><?php echo category_description( $cat ); ?></div>
	</div>
	<?php } wp_reset_query(); ?>
	<div class="clear"></div>
	<div id="grid-carousel" class="owl-carousel grid-carousel">
		<?php $loop = new WP_Query( array( 'category__and' => zm_get_option('grid_carousel_id'), 'posts_per_page' => zm_get_option('grid_carousel_n'), 'post__not_in' => get_option( 'sticky_posts'), 'post__not_in' => $do_not_duplicate ) );while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('grid-carousel-main sup bk'); ?> >
			<div class="grid-scrolling-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
			<div class="clear"></div>
			<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<span class="grid-inf">
				<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
					<span class="grid-inf-l">
					<?php views_span(); ?>
					<?php echo t_mark(); ?>
					</span>
				<?php } else { ?>
					<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
					<?php views_span(); ?>
					<span class="grid-inf-l">
						<span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span>
						<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						<?php echo t_mark(); ?>
					</span>
				<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
	<div class="clear"></div>
	<div class="grid-cat-more" <?php aos_a(); ?>><a href="<?php echo $cat_links; ?>" title="<?php _e( '更多', 'begin' ); ?>"><i class="be be-more"></i></a></div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cat_lead')) { ?>
<div class="cms-cat-lead sort" name="<?php echo zm_get_option('cat_lead_s'); ?>">
	<?php $display_categories = explode(',',zm_get_option('cat_lead_id') ); foreach ($display_categories as $category) { ?>
		<?php if (zm_get_option('no_cat_child')) { ?>
			<?php query_posts( array('cat' => $category ) ); ?>
			<?php query_posts( array( 'showposts' => 1, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
		<?php } else { ?>
			<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
		<?php } ?>

		<div class="cat-container bk" <?php aos_a(); ?>>
			<h3 class="cat-title bkx ms"><a href="<?php echo get_category_link($category);?>"><?php cat_module_title(); ?></a></h3>
			<div class="clear"></div>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('post cms-cat-lead-post ms bky doclose'); ?>>
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
					</figure>
					<?php header_title(); ?>
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>

					<div class="entry-content">
						<div class="archive-content">
							<?php begin_trim_words(); ?>
						</div>
						<div class="clear"></div>
						<span class="entry-meta lbm">
							<?php begin_entry_meta(); ?>
						</span>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="clear"></div>

		<div class="cms-news-grid-container<?php if (!zm_get_option('no_lead_img')) { ?> hide-lead-img bk ms<?php } ?>" <?php aos_a(); ?>>
			<?php if (zm_get_option('no_cat_child')) { ?>
				<?php query_posts( array( 'showposts' => zm_get_option('cat_lead_n'), 'cat' => $category, 'offset' => 1, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
			<?php } else { ?>
				<?php query_posts( array( 'showposts' => zm_get_option('cat_lead_n'), 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_duplicate ) ); ?>
			<?php } ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (zm_get_option('no_cat_child')) { ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('bk hy ms glx'); ?>>
				<?php } else { ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class('bk hy ms glx'); ?>>
				<?php } ?>
					<?php if (zm_get_option('no_lead_img')) { ?>
						<figure class="thumbnail">
							<?php zm_thumbnail(); ?>
						</figure>
					<?php } ?>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title over srm"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>

					<div class="entry-content">
						<?php if (zm_get_option('no_lead_img')) { ?>
							<div class="archive-content">
								<?php if (has_excerpt('')){
										echo wp_trim_words( get_the_excerpt(), 30, '...' );
									} else {
										$content = get_the_content();
										$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
										echo wp_trim_words( $content, 35, '...' );
							        }
								?>
							</div>
						<?php } ?>
						<?php if (zm_get_option('no_lead_img')) { ?>
							<span class="entry-meta lbm">
								<?php begin_entry_meta(); ?>
							</span>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	<?php } ?>
</div>
<?php } ?>
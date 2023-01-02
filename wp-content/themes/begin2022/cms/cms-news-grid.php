<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="cms-news-grid-container">
	<div class="marked-ico da" <?php aos_a(); ?>><?php _e( '最近更新', 'begin' ); ?></div>
	<?php if (zm_get_option('cms_top')) { ?>
		<?php 
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$do_show[] = '';
			$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'category__not_in' => explode(',', zm_get_option('not_news_n')), 'post__not_in' => $do_show, 'meta_query' => array( array( 'key' => 'cms_top', 'compare' => 'NOT EXISTS')), 'ignore_sticky_posts' => 1, 'paged' => $paged ));
		?>
	<?php } else { ?>
		<?php 
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$do_show[] = '';
			$recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'post__not_in' => $do_show, 'category__not_in' => explode(',',zm_get_option('not_news_n')), 'ignore_sticky_posts' => 1, 'paged' => $paged ) );
		?>
	<?php } ?>
	<?php while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; ?>
	<?php if(zm_get_option('news_n') < 4 ) { ?>
		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk gl2'); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk gl'); ?>>
	<?php } ?>

		<?php get_template_part( 'template/new' ); ?>

		<?php if ( has_post_format( 'link' ) ) { ?>

			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
			</figure>
			<header class="entry-header">
				<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
				<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
					<h2 class="entry-title over"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
				<?php } else { ?>
					<h2 class="entry-title over"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php } ?>
			</header>

			<div class="entry-content">
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
				<span class="entry-meta lbm">
					<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
					<span class="date"><?php time_ago( $time_type ='post' ); ?>&nbsp;</span>
					<?php views_span(); ?>
					<?php } else { ?>
						<?php begin_entry_meta(); ?>
					<?php } ?>
				</span>
				<div class="clear"></div>
			</div>

			<?php } else { ?>

			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
			</figure>
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>

			<div class="entry-content">
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
				<span class="entry-meta lbm">
					<?php begin_entry_meta(); ?>
				</span>
				<div class="clear"></div>
			</div>

			<?php } ?>
		</article>
	<?php endwhile; ?>
	<div class="clear"></div>
</div>

<?php if (zm_get_option('post_img')) { ?>
	<div class="line-four" <?php aos_a(); ?>>
		<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
	</div>
<?php } ?>
<div class="clear"></div>
<?php get_template_part('ad/ads', 'cms'); ?>
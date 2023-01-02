<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_top')) { ?>
<div class="cms-news-grid-container cms-top-item sort" name="<?php echo zm_get_option('cms_top_s'); ?>">
	<div class="marked-ico da hz" <?php aos_a(); ?>><?php _e( '推荐', 'begin' ); ?></div>
	<?php query_posts( array ( 'meta_key' => 'cms_top', 'showposts' => zm_get_option('cms_top_n'), 'post__not_in' => get_option( 'sticky_posts') ) ); if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php if (zm_get_option('cms_top_n') < 4 ) { ?>
		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk gt2'); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk gt'); ?>>
	<?php } ?>

		<?php if ( has_post_format( 'link' ) ) { ?>

			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
			</figure>
			<header class="entry-header">
				<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
				<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
					<h2 class="entry-title over"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
				<?php } else { ?>
					<h2 class="entry-title over"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<?php } ?>
			</header>

			<div class="entry-content">
				<div class="archive-content"></div>
				<span class="entry-meta lbm">
					<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
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
				<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
	<?php else : ?>
		<div class="be-none da bk">编辑文章，勾选“首页推荐文章”</div>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>
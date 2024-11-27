<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="cms-news-grid-container cms-news-item">
	<div class="marked-ico" <?php aos_a(); ?>><?php _e( '最新', 'begin' ); ?></div>
	<?php 
		$sticky = be_get_option( 'news_grid_sticky' ) ? '0' : '1';
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$notcat = be_get_option( 'not_news_n' ) ? implode( ',', be_get_option( 'not_news_n') ) : '';
		$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : '';

		$args = array(
			'posts_per_page'      => be_get_option( 'news_n' ),
			'category__not_in'    => explode( ',', $notcat ),
			'post__not_in'        => $top_id,
			'ignore_sticky_posts' => $sticky,
			'paged'               => $paged
		);

		$recent = new WP_Query($args);
	?>
	<?php while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; ?>
	<?php if (be_get_option('news_n') < 4 ) { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post ms gl2" <?php aos_a(); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post ms gl" <?php aos_a(); ?>>
	<?php } ?>

		<?php get_template_part( 'template/new' ); ?>

		<?php if ( has_post_format( 'link' ) ) { ?>

			<figure class="thumbnail">
				<?php echo zm_thumbnail_link(); ?>
			</figure>
			<header class="entry-header">
				<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
				<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
					<h2 class="entry-title over"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
				<?php } else { ?>
					<h2 class="entry-title over"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?> <?php echo goal(); ?>><?php the_title(); ?></a></h2>
				<?php } ?>
			</header>

			<div class="entry-content">
				<div class="archive-content"></div>
				<span class="entry-meta lbm">
					<?php if ( get_post_meta( get_the_ID(), 'direct', true ) ) { ?>
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
				<?php echo zm_thumbnail(); ?>
			</figure>
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>

			<div class="entry-content">
				<span class="entry-meta lbm">
					<?php begin_entry_meta(); ?>
				</span>
				<div class="clear"></div>
			</div>

			<?php } ?>
		</article>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 最新文章', $number = 'news_s' ); ?>
	<div class="clear"></div>
</div>

<?php if ( be_get_option( 'cms_new_post_img' ) ) { ?>
	<div class="line-four betip" <?php aos_a(); ?>>
		<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
	</div>
<?php } ?>
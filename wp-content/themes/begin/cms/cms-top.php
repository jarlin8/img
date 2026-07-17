<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_top' ) ) { ?>
<div class="cms-news-grid-container cms-top-item">
	<?php
		$args = array(
			'post__in'  => explode( ',', be_get_option( 'cms_top_id' ) ),
			'post_type' => 'any',
			'orderby'   => 'post__in', 
			'order'     => 'DESC',
			'ignore_sticky_posts' => true,
			);

		$query = new WP_Query( $args );
	?>

	<?php if ( be_get_option( 'top_only' ) ) { ?>
		<?php if ( $query->have_posts() ) : ?>
			<div class="marked-ico"><?php _e( '推荐', 'begin' ); ?></div>
		<?php endif; ?>
	<?php } else { ?>
		<div class="marked-space"></div>
	<?php } ?>

	<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>

	<?php if ( $query->found_posts < 4 ) { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post ms gt2" <?php aos_a(); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post ms gt" <?php aos_a(); ?>>
	<?php } ?>

		<?php if ( has_post_format( 'link' ) ) { ?>

			<figure class="thumbnail">
				<?php echo zm_thumbnail_link(); ?>
				<?php if ( ! be_get_option( 'top_only' ) ) { ?>
					<div class="marked-ico each"><?php _e( '推荐', 'begin' ); ?></div>
				<?php } ?>
			</figure>
			<header class="entry-header">
				<?php if ( get_post_meta( get_the_ID(), 'direct', true ) ) { ?>
				<?php $direct = get_post_meta( get_the_ID(), 'direct', true ); ?>
					<h2 class="entry-title over"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
				<?php } else { ?>
					<h2 class="entry-title over"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></h2>
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
				<?php if ( ! be_get_option( 'top_only' ) ) { ?>
					<div class="marked-ico each"><?php _e( '推荐', 'begin' ); ?></div>
				<?php } ?>
			</figure>
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
	<?php else : ?>
		<div class="be-none">首页设置 → 杂志布局 → 推荐文章，输入文章ID</div>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 推荐文章', $number = 'cms_top_s' ); ?>
	<div class="clear"></div>
</div>
<?php } ?>
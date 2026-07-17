<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_hot' ) ) { ?>
<div class="cms-hot betip">
	<?php 
		$hot = (array) be_get_option( 'cms_hot_item' );
		foreach ( $hot as $items ) {
	?>
		<?php
			$args = array(
				'post__in'  => explode( ',', $items['cms_hot_id'] ),
				'post_type' => 'any',
				'orderby'   => 'post__in', 
				'order'     => 'DESC',
				'ignore_sticky_posts' => true,
				);

			$query = new WP_Query( $args );
		?>
		<div class="cms-hot-box hot<?php echo be_get_option( 'cms_hot_f' ); ?>">
			<div class="boxs1">
				<div class="cms-hot-main ms betip" <?php aos_a(); ?>>
					<?php if ( ! empty( $items['cms_hot_title'] ) ) { ?>
						<h3 class="cms-hot-head">
							<?php if ( ! empty( $items['cms_hot_svg'] ) ) { ?>
								<svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $items['cms_hot_svg']; ?>"></use></svg>
							<?php } ?>
							<?php if ( ! empty( $items['cms_hot_ico'] ) ) { ?>
								<i class="<?php echo $items['cms_hot_ico']; ?>"></i>
							<?php } ?>
							<?php echo $items['cms_hot_title']; ?>
							<?php if ( ! empty( $items['cms_hot_more'] ) ) { ?>
								<a href="<?php echo $items['cms_hot_more']; ?>" target="_blank"><?php more_i(); ?></a>
							<?php } ?>
						</h3>
					<?php } ?>
		
					<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="cms-hot-item">
							<span class="thumbnail">
								<?php echo zm_thumbnail(); ?>
							</span>
							<span class="cms-hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><?php the_title(); ?></a></span>
							<?php views_span(); ?>
							<?php grid_meta(); ?>
						</div>
						<?php endwhile; ?>
					<?php else : ?>
						<a href="<?php home_url( '/' ); ?>wp-admin/admin.php?page=be-options#tab=%e6%9d%82%e5%bf%97%e5%b8%83%e5%b1%80/%e7%83%ad%e9%97%a8%e6%8e%a8%e8%8d%90" target="_blank">杂志布局 → 热门推荐 → 输入文章ID</a>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 热门推荐', $number = 'cms_hot_s' ); ?>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (be_get_option('grid_carousel')) { ?>
<div class="grid-carousel-box betip" <?php aos_a(); ?>>
	<?php $becat = explode( ',', be_get_option( 'grid_carousel_id' ) ); foreach ( $becat as $category ) { ?>
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_a(); ?>><a href="<?php echo get_category_link( $category ); ?>"><?php echo get_cat_name( $category ); ?></a></h3>
			<?php if ( be_get_option( 'grid_carousel_des' ) ) { ?><div class="grid-cat-des" <?php aos_a(); ?>><?php echo category_description( $category ); ?></div><?php } ?>
		</div>
	<?php } ?>
	<div class="clear"></div>
	<div id="grid-carousel" class="owl-carousel grid-carousel">
		<?php
			if ( be_get_option( 'no_grid_cat_child' ) ) {
				$cat = 'category';
			} else {
				$cat = 'category__in';
			}
			$posts = get_posts( array(
				$cat             => be_get_option( 'grid_carousel_id' ),
				'posts_per_page' => be_get_option( 'grid_carousel_n' ),
				'post_status'    => 'publish',
				'post__not_in'   => $do_not_duplicate
			) );
		?>
		<?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
			<div id="post-<?php the_ID(); ?>" class="post-item-list post grid-carousel-main sup">
				<div class="grid-scrolling-thumbnail"><?php echo zm_thumbnail_scrolling(); ?></div>
				<div class="clear"></div>
				<?php the_title( sprintf( '<h2 class="grid-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<span class="grid-inf">
					<?php if ( get_post_meta( get_the_ID(), 'link_inf', true ) ) { ?>
						<span class="link-inf"><?php $link_inf = get_post_meta( get_the_ID(), 'link_inf', true );{ echo $link_inf;} ?></span>
						<span class="grid-inf-l">
						<?php views_span(); ?>
						<?php echo t_mark(); ?>
						</span>
					<?php } else { ?>
						<?php if ( be_get_option( 'meta_author' ) ) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
						<?php views_span(); ?>
						<span class="grid-inf-l">
							<?php echo be_vip_meta(); ?>
							<?php grid_meta(); ?>
							<?php if ( get_post_meta( get_the_ID(), 'zm_like', true ) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							<?php echo t_mark(); ?>
						</span>
					<?php } ?>
				</span>
				<div class="clear"></div>
			</div>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</div>
	<div class="clear"></div>
	<div class="grid-cat-more" <?php aos_a(); ?>><a href="<?php echo get_category_link( $category ); ?>"><i class="be be-more"></i></a></div>
	<?php be_help( $text = '首页设置 → 分类图片 → 分类滚动模块' ); ?>
</div>
<?php } ?>
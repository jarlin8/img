<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'grid_cat_new' ) ) { ?>
<div class="grid-cat-box" <?php aos_a(); ?>>

	<div class="grid-cat-new-box">
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title" <?php aos_b(); ?>><?php _e( '最近更新', 'begin' ); ?></h3>
		</div>
		<div class="clear"></div>

		<div class="ajax-cat-cntent-grid cat-border catpast grid-cat-area grid-cat-<?php echo be_get_option( 'grid_new_f' ); ?>"></div>
		<div class="ajax-new-cntent-grid netcurrent">
			<div class="grid-cat-area grid-cat-<?php echo be_get_option( 'grid_new_f' ); ?>">
				<?php 
					$recent = new WP_Query(
						array(
							'post__not_in'   => explode( ',', be_get_option( 'catimg_top_id' ) ),
							'posts_per_page' => be_get_option( 'grid_cat_news_n' ), 
						)
					);
				?>
				<?php while( $recent->have_posts() ) : $recent->the_post(); ?>
				<?php grid_new(); ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php be_help( $text = '首页设置 → 分类图片 → 最新文章' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
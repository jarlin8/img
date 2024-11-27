<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cat_tdk' ) ) { ?>
<div class="line-one line-one-no-img betip">
	<?php $cmscatlist = explode( ',', be_get_option( 'cat_tdk_id' ) ); foreach ( $cmscatlist as $category ) { ?>

		<div class="cat-container ms" <?php aos_a(); ?>>
			<h3 class="cat-title">
				<a href="<?php echo get_category_link( $category ); ?>" rel="bookmark" <?php echo goal(); ?>>
					<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
						<?php if ( get_option( 'zm_taxonomy_icon' . $category ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $category ); ?>"></i><?php } ?>
						<?php if ( get_option( 'zm_taxonomy_svg' . $category ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $category ); ?>"></use></svg><?php } ?>
						<?php if ( ! get_option( 'zm_taxonomy_icon' . $category ) && ! get_option( 'zm_taxonomy_svg'.$category ) ) { ?><?php title_i(); ?><?php } ?>
					<?php } else { ?>
						<?php title_i(); ?>
					<?php } ?>
					<?php echo get_cat_name( $category ); ?>
					<?php more_i(); ?>
				</a>
			</h3>

			<div class="clear"></div>
			<div class="cat-tdk-main">
				<ul class="cat-tdk-list">
					<?php
						$lists = get_posts( array(
							'posts_per_page' => be_get_option( 'cat_tdk_n' ),
							'post_status'    => 'publish',
							'category'       => $category
						) );

						foreach ( $lists as $post ) : setup_postdata( $post );
							$cut = be_get_option( 'cat_tdk_cut_title' ) ? ' cut' : '';
							echo '<li class="cat-tdk-area' . $cut . '">';
							$post_tags = get_the_tags();
							if ( $post_tags ) {
								$first_tag = $post_tags[0];
								echo '<a class="cat-tdk-tag" href="' . get_tag_link( $first_tag->term_id ) . '" rel="bookmark" ' . goal() . '>' . $first_tag->name . '</a>';
							}
							the_title( sprintf( '<h2 class="tdk-entry-title over"><a class="cat-tdk-title" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' );
							echo '</li>';
						endforeach; 
						wp_reset_postdata();
					?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>

	<?php } ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 标签标题', $number = 'cat_tdk_s' ); ?>
</div>
<?php } ?>
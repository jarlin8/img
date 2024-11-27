<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_cat_b' ) ) {
	if ( ! co_get_option( 'cat_b_bg' ) || ( co_get_option( 'cat_b_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'cat_b_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'cat_b_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-cat-b<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-cat">
			<?php $categories =  explode(',', co_get_option( 'group_cat_b_id' ) );
				foreach ( $categories as $category ) {
				$cat = co_get_option( 'group_no_cat_child' ) ? 'category' : 'category__in';
				$becat = $category;
				if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
					$becat = icl_object_id( $category, 'category', true );
				}
			?>

				<div class="gr2">
					<div class="gr-cat-box">
						<h3 class="gr-cat-title" <?php aos_f(); ?>><a href="<?php echo get_category_link( $category ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo get_cat_name( $becat ); ?><span class="gr-cat-more"><i class="be be-more"></i></span></a></h3>
						<div class="clear"></div>
						<div class="gr-cat-area">
							<?php if ( co_get_option( 'group_cat_b_top' ) ) { ?>

								<?php
									$imgt = get_posts( array(
										'meta_key'       => 'cat_top',
										'posts_per_page' => 1,
										'post_status'    => 'publish',
										$cat             => $category,
									) );
								?>
								<?php if ( empty( $imgt ) ) { ?>
									<div class="group-top-tip" style="font-weight: 700;padding: 10px; 0">编辑该分类一篇文章，在“将文章添加到”面板中勾选“分类推荐文章”</div>
								<?php } else { ?>
									<?php foreach ( $imgt as $post ) : setup_postdata( $post ); $grouptop[] = $post->ID; $has_top_post = true; ?>
											<figure class="gr-thumbnail" <?php aos_b(); ?>><?php echo zm_long_thumbnail(); ?></figure>
											<div class="be-aos" <?php aos_f(); ?>><?php the_title( sprintf( '<h2 class="gr-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?></div>
											<div class="clear"></div>
										<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								<?php } ?>

								<div class="clear"></div>
								<ul class="gr-cat-list" <?php aos_b(); ?>>
									<?php
										$imgb = get_posts( array(
											'posts_per_page' => co_get_option( 'group_cat_b_n' ),
											'post_status'    => 'publish',
											$cat             => $category,
											'post__not_in'   => $has_top_post ? $grouptop : array(),
										) );
									?>
									<?php foreach ( $imgb as $post ) : setup_postdata( $post ); ?>
										<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></li>
										<div class="be-aos" <?php aos_b(); ?>><?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' ); ?></div>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</ul>
							<?php } else { ?>
								<?php
									$imgt = get_posts( array(
										'posts_per_page' => 1,
										'post_status'    => 'publish',
										$cat             => $category,
									) );
								?>
								<?php foreach ( $imgt as $post ) : setup_postdata( $post ); ?>
									<figure class="gr-thumbnail" <?php aos_b(); ?>><?php echo zm_long_thumbnail(); ?></figure>
									<div class="be-aos" <?php aos_f(); ?>><?php the_title( sprintf( '<h2 class="gr-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?></div>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>

								<div class="clear"></div>
								<ul class="gr-cat-list" <?php aos_f(); ?>>
									<?php
										$imgb = get_posts( array(
											'posts_per_page' => co_get_option( 'group_cat_b_n' ),
											'post_status'    => 'publish',
											$cat             => $category,
											'offset'         => 1,
										) );
									?>
									<?php foreach ( $imgb as $post ) : setup_postdata( $post ); ?>
										<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></li>
										<?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</ul>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php co_help( $text = '公司主页 → 新闻资讯B', $number = 'group_cat_b_s' ); ?>
	</div>
</div>
<?php } ?>
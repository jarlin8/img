<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_cat_c' ) ) {
	if ( ! be_build( get_the_ID(), 'cat_c_bg' ) || ( be_build( get_the_ID(), 'cat_c_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'cat_c_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'cat_c_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-cat-c<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-cat">
			<?php $categories =  explode(',', be_build( get_the_ID(), 'group_cat_c_id' ) );
				foreach ( $categories as $category ) {
				$cat = be_build( get_the_ID(), 'group_no_cat_child' ) ? 'category' : 'category__in';
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
							<?php if (be_build( get_the_ID(),'group_cat_c_img')) { ?>
								<?php
									$imgt = get_posts( array(
										'posts_per_page' => 1,
										'post_status'    => 'publish',
										$cat             => $category,
										'post__not_in'   => $do_not_cat,
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
										$imgt = get_posts( array(
											'posts_per_page' => be_build( get_the_ID(), 'group_cat_c_n' ),
											'post_status'    => 'publish',
											$cat             => $category,
											'post__not_in'   => $do_not_cat,
											'offset'         => 1,
										) );
									?>

									<?php foreach ( $imgt as $post ) : setup_postdata( $post ); ?>
										<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time( 'H:i:s' ); ?>"><?php the_time( 'm/d' ) ?></time></li>
										<?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</ul>
							<?php } else { ?>
								<div class="clear"></div>
								<ul class="gr-cat-list" <?php aos_f(); ?>>
									<?php
										$imgb = get_posts( array(
											'posts_per_page' => be_build( get_the_ID(), 'group_cat_c_n' ),
											'post_status'    => 'publish',
											$cat             => $category,
											'post__not_in'   => $do_not_cat,
										) );
									?>
									<?php foreach ( $imgb as $post ) : setup_postdata( $post ); ?>
										<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time( 'H:i:s' ); ?>"><?php the_time( 'm/d' ) ?></time></li>
										<?php the_title( sprintf( '<li class="list-title"><h2 class="group-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2></li>' ); ?>
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
		<?php bu_help( $text = '新闻资讯C', $number = 'group_cat_c_s' ); ?>
	</div>
</div>
<?php } ?>
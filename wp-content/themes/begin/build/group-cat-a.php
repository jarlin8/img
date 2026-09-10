<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_cat_a' ) ) {
	if ( ! be_build( get_the_ID(), 'cat_a_bg' ) || ( be_build( get_the_ID(), 'cat_a_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'cat_a_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'cat_a_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-cat-a notext<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-cat">
			<?php $display_categories =  explode( ',', be_build( get_the_ID(), 'group_cat_a_id' ) );
				foreach ( $display_categories as $category ) {
				$has_top_post = false;
				$cat = be_build( get_the_ID(), 'group_no_cat_child' ) ? 'category' : 'category__in';
				$becat = $category;
				if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
					$becat = icl_object_id( $category, 'category', true );
				}
			?>
			<div class="gr2">
				<div class="gr-cat-box">
					<h3 class="gr-cat-title" <?php aos_b(); ?>><a href="<?php echo get_category_link( $category ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo get_cat_name( $becat ); ?><span class="gr-cat-more"><i class="be be-more"></i></span></a></h3>
					<div class="clear"></div>
					<div class="gr-cat-area">
						<?php if ( be_build( get_the_ID(), 'group_cat_a_top' ) ) { ?>
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
									<div id="post-<?php the_ID(); ?>" class="gr-img-t">
										<figure class="gr-thumbnail"><?php echo zm_long_thumbnail(); ?></figure>
										<?php the_title( sprintf( '<h2 class="gr-title-img"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										<div class="clear"></div>
									</div>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							<?php } ?>
							<div class="clear"></div>

							<div class="gr-cat-img">
								<?php
									$imgb = get_posts( array(
										'posts_per_page' => be_build( get_the_ID(), 'group_cat_a_n' ),
										'post_status'    => 'publish',
										$cat             => $category,
										'post__not_in'   =>  $has_top_post ? $grouptop : array(),
									) );
								?>

								<?php foreach ( $imgb as $post ) : setup_postdata( $post ); ?>
									<div class="cat-gr2">
										<div id="post-<?php the_ID(); ?>" class="gr-img">
											<figure class="gr-a-thumbnail picture-cms-img" <?php aos_b(); ?>><?php echo zm_thumbnail(); ?></figure>
											<?php the_title( sprintf( '<div class="gr-img-title"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></div>' ); ?>
											<div class="clear"></div>
										</div>
									</div>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						<?php } else { ?>
							<?php
								$imgt = get_posts( array(
									'posts_per_page' => 1,
									'post_status'    => 'publish',
									$cat             => $category,
								) );
							?>
							<?php foreach ( $imgt as $post ) : setup_postdata( $post ); ?>
								<div id="post-<?php the_ID(); ?>" class="gr-img-t">
									<figure class="gr-thumbnail" <?php aos_b(); ?>><?php echo zm_long_thumbnail(); ?></figure>
									<?php the_title( sprintf( '<h2 class="gr-title-img bgb"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<div class="clear"></div>
								</div>
							<?php endforeach; ?>
							<?php wp_reset_postdata(); ?>

							<div class="clear"></div>
							<div class="gr-cat-img">
								<?php
									$imgb = get_posts( array(
										'posts_per_page' => be_build( get_the_ID(), 'group_cat_a_n' ),
										'post_status'    => 'publish',
										$cat             => $category,
										'offset'         => 1,
									) );
								?>
								<?php foreach ( $imgb as $post ) : setup_postdata( $post ); ?>
									<div id="post-<?php the_ID(); ?>" class="cat-gr2">
										<div class="gr-img" <?php aos_b(); ?>>
											<figure class="gr-a-thumbnail picture-cms-img"><?php echo zm_thumbnail(); ?></figure>
											<?php the_title( sprintf( '<h2 class="gr-img-title"><a href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											<div class="clear"></div>
										</div>
									</div>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						<?php } ?>
					</div>
				</div>

			</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php bu_help( $text = '新闻资讯A', $number = 'group_cat_a_s' ); ?>
	</div>
</div>
<?php } ?>
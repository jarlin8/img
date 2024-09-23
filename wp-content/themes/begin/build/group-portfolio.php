<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_portfolio' ) ) {
	if ( ! be_build( get_the_ID(), 'portfolio_bg' ) || ( be_build( get_the_ID(), 'portfolio_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'portfolio_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'portfolio_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php $categories = explode( ',', be_build( get_the_ID(), 'group_portfolio_id' ) );
		foreach ( $categories as $category ) {
		$cat = ( be_build( get_the_ID(), 'group_no_cat_child' ) ) ? 'category' : 'category__in';
		$becat = $category;
		if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
			$becat = icl_object_id( $category, 'category', true );
		}
	?>
		<div class="betip line-group-portfolio g-row g-line notext<?php echo $bg; ?>" <?php aos(); ?>>
			<div class="g-col">
				<div class="flexbox-grid">
					<div class="group-title" <?php aos_b(); ?>>
						<a href="<?php echo get_category_link( $category ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>>
							<?php if ( be_build( get_the_ID(), 'group_portfolio_id' ) ) { ?>
								<h3><?php echo get_cat_name( $becat ); ?></h3>
							<?php } else { ?>
								<h3>未分类</h3>
								<div class="group-des">公司主页 → 分类组合，输入分类ID</div>
							<?php } ?>
						</a>
						<?php if ( category_description( $category ) ) { ?>
							<div class="group-des"><?php echo category_description( $category ); ?></div>
						<?php } ?>
						<div class="clear"></div>
					</div>


					<div class="clear"></div>
					<div class="cat-portfolio-main">
						<div class="cat-portfolio-area">
							<div class="cat-portfolio-img">
								<?php 
									$args = array(
										'posts_per_page' => 4,
										'post_status'    => 'publish',
										$cat             => $category
									);
									$query = new WP_Query( $args );
								?>
								<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
								<div id="post-<?php the_ID(); ?>" class="cat-portfolio-item-img" <?php aos_a(); ?>>
									<figure class="thumbnail">
										<?php echo zm_thumbnail(); ?>
									</figure>
									<div class="clear"></div>
									<?php the_title( sprintf( '<h2 class="portfolio-img-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								</div>
								<?php endwhile; endif; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>

						<div class="cat-portfolio-area">
							<ul class="cat-portfolio-list lic">
								<?php 
									$args = array(
										'posts_per_page' => 10,
										'offset'         => 4,
										'post_status'    => 'publish',
										$cat             => $category
									);
									$s = 0;
									$query = new WP_Query( $args );
								?>
								<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); $s++; ?>
									<li id="post-<?php the_ID(); ?>" class="portfolio-list-title high-<?php echo mt_rand(1, $s); ?>" <?php aos_a(); ?>>
										<?php the_title( sprintf( '<a class="srm" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a>' ); ?>
									</li>
								<?php endwhile; endif; ?>
								<?php wp_reset_postdata(); ?>
								<div class="clear"></div>
							</ul>
						</div>

						<div class="cat-portfolio-area">
							<div class="cat-portfolio-card">
								<?php 
									$args = array(
										'posts_per_page' => '3',
										'orderby'        => 'date',
										'order'          => 'ASC',
										'post_status'    => 'publish',
										$cat             => $category
									);
									$query = new WP_Query( $args );
								?>
								<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
									<div id="post-<?php the_ID(); ?>" class="portfolio-card-item" <?php aos_a(); ?>>
										<figure class="thumbnail">
											<?php echo zm_thumbnail(); ?>
										</figure>
										<div class="portfolio-card-content">
											<?php the_title( sprintf( '<h2 class="portfolio-card-title over"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											<span class="entry-meta">
												<?php begin_grid_meta(); ?>
											</span>
											<div class="clear"></div>
										</div>
									</div>
								<?php endwhile; endif; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>
					</div>
					<?php bu_help( $text = '分类组合', $number = 'group_portfolio_s' ); ?>
					<div class="group-cat-img-more"><a href="<?php echo get_category_link( $category ); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
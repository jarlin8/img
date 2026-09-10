<?php 
// 展示
if ( ! defined( 'ABSPATH' ) ) exit;
if ( be_build( get_the_ID(), 'group_img' ) ) {
	if ( ! be_build( get_the_ID(), 'img_bg' ) || ( be_build( get_the_ID(), 'img_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'img_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'img_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php if ( be_build( get_the_ID(), 'group_img_id' ) ) { ?>
		<?php
			$cat = ( be_build( get_the_ID(), 'group_no_cat_child' ) ) ? true : false;
			$tax = get_taxonomies();
			$tax_terms = get_terms( $tax , array(
				'include' => explode( ',', be_build( get_the_ID(), 'group_img_id' ) ),
				'orderby' => 'include',
				'order'   => 'ASC',
			));

			if ( $tax_terms ) {
				foreach ( $tax_terms as $tax_term ) { ?>

				<div class="g-row g-line group-cat-img-line<?php echo $bg; ?>" <?php aos(); ?>>
					<div class="g-col">
						<div class="group-features">
							<div class="group-title" <?php aos_b(); ?>>
								<h3><?php echo $tax_term->name; ?></h3>
								<?php if ( $tax_term->description ) { ?>
									<div class="group-des"><?php echo $tax_term->description; ?></div>
								<?php } ?>
								<div class="clear"></div>
							</div>

							<div class="section-box">
								<?php
									$args = array(
										'post_type' => 'any',
										'tax_query' => array(
											array(
												'taxonomy'         => $tax_term->taxonomy,
												'field'            => 'term_id',
												'terms'            => $tax_term->term_id,
												'include_children' => $cat,
											),
										),

										'post_status'         => 'publish',
										'posts_per_page'      => be_build( get_the_ID(), 'group_img_n' ),
										'orderby'             => 'date',
										'order'               => 'DESC',
										'ignore_sticky_posts' => 1,
									);

									$query = new WP_Query( $args );
								?>

								<?php $build = get_the_ID(); ?>

								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<div class="g4 g<?php echo be_build( $build, 'group_img_f' ); ?>">
										<div class="box-4">
											<figure class="section-thumbnail" <?php aos_b(); ?>>
												<?php echo tao_thumbnail(); ?>
											</figure>
											<?php the_title( sprintf( '<h2 class="g4-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										</div>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
								<div class="clear"></div>
								<div class="group-cat-img-more"><a href="<?php echo get_category_link( $tax_term->term_id );?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
							</div>
						</div>

						<?php bu_help( $text = '展示', $number = 'group_img_s' ); ?>
						<div class="clear"></div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>
		<div class="g-row g-line group-cat-img-line" <?php aos(); ?>>
			<div class="g-col">
				<div class="group-features">
					<div class="group-title" <?php aos_b(); ?>>
						<h3>未输入分类ID</h3>
						<div class="clear"></div>
					</div>
					<div style="text-align: center;">展示 → 输入分类ID</div>
				</div>
				<?php bu_help( $text = '展示', $number = 'group_img_s' ); ?>
				<div class="clear"></div>
			</div>
		</div>

	<?php } ?>
<?php } ?>
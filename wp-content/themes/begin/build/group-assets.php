<?php
// 会员商品
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_assets' ) ) {
	if ( ! be_build( get_the_ID(), 'assets_bg' ) || ( be_build( get_the_ID(), 'assets_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'assets_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'assets_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php if ( ! be_build( get_the_ID(), 'group_assets_get' ) || ( be_build( get_the_ID(), "group_assets_get" ) == 'cat' ) ) { ?>
		<?php if ( be_build( get_the_ID(), 'group_assets_id' ) ) { ?>
			<?php
				$cat = ( be_build( get_the_ID(), 'group_no_cat_child' ) ) ? true : false;
				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_build( get_the_ID(), 'group_assets_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				if ( $tax_terms ) {
					foreach ( $tax_terms as $tax_term ) { ?>
						<div class="betip line-group-assets g-row g-line group-assets-<?php echo be_build( get_the_ID(), 'group_assets_f' ); ?><?php echo $bg; ?>" <?php aos(); ?>>
						<div class="g-col">
							<div class="flexbox-grid">
								<div class="group-title" <?php aos_b(); ?>>
									<h3><?php echo $tax_term->name; ?></h3>
									<?php if ( $tax_term->description ) { ?>
										<div class="group-des"><?php echo $tax_term->description; ?></div>
									<?php } ?>
									<div class="clear"></div>
								</div>
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
											'posts_per_page'      => be_build( get_the_ID(), 'group_assets_n' ),
											'orderby'             => 'date',
											'order'               => 'DESC',
											'ignore_sticky_posts' => 1,
										);

										$query = new WP_Query( $args );
										while ( $query->have_posts() ) : $query->the_post();
										require get_template_directory() . '/template/assets.php';
										endwhile; wp_reset_postdata();
									?>
								<div class="clear"></div>
								<div class="group-cat-img-more"><a href="<?php echo get_category_link( $tax_term->term_id );?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
							</div>
							<?php bu_help( $text = '会员商品', $number = 'group_assets_s' ); ?>
						</div>
						<div class="clear"></div>
					</div>
				<?php } ?>
			<?php } ?>

		<?php } else { ?>
			<div class="g-row g-line group-cat-img-line" <?php aos(); ?>>
				<div class="g-col">
					<div class="group-id-tip">
						<div class="group-title" <?php aos_b(); ?>>
							<h3>未输入分类ID</h3>
						</div>
						<div style="text-align: center;">会员商品 → 输入分类ID</div>
					</div>
					<?php bu_help( $text = '会员商品', $number = 'group_assets_s' ); ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if ( be_build( get_the_ID(), 'group_assets_get' ) == 'post' ) { ?>
		<div class="betip line-group-assets g-row g-line group-assets-<?php echo be_build( get_the_ID(), 'group_assets_f' ); ?>" <?php aos(); ?>>
			<div class="g-col">
				<div class="flexbox-grid">
					<div class="group-title" <?php aos_b(); ?>>
						<?php if ( ! be_build( get_the_ID(), 'group_assets_t' ) == '' ) { ?>
							<h3><?php echo be_build( get_the_ID(), 'group_assets_t' ); ?></h3>
						<?php } ?>
						<?php if ( ! be_build( get_the_ID(), 'group_assets_des' ) == '' ) { ?>
							<div class="group-des"><?php echo be_build( get_the_ID(), 'group_assets_des' ); ?></div>
						<?php } ?>
						<div class="clear"></div>
					</div>
					<?php
						$args = array(
							'post__in'  => explode( ',', be_build( get_the_ID(), 'group_assets_post_id' ) ),
							'orderby'   => 'post__in', 
							'post_type' => 'any',
							'order'     => 'DESC',
							'ignore_sticky_posts' => true,
							);
						$query = new WP_Query( $args );

						if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
							require get_template_directory() . '/template/assets.php';
						endwhile;
						else :
							echo '<div class="be-none">会员商品，输入文章ID</div>';
						endif;
						wp_reset_postdata();
					?>
					<div class="clear"></div>
					<div class="group-cat-img-more"><a href="<?php echo get_category_link( $category );?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
				</div>
				<?php bu_help( $text = '会员商品', $number = 'group_assets_s' ); ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
<?php } ?>
<?php
// 会员商品
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_assets' ) ) {
	if ( ! co_get_option( 'assets_bg' ) || ( co_get_option( 'assets_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'assets_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'assets_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php if ( ! co_get_option( 'group_assets_get' ) || ( co_get_option( "group_assets_get" ) == 'cat' ) ) { ?>
		<?php if ( co_get_option( 'group_assets_id' ) ) { ?>
			<?php
				$cat = ( co_get_option( 'group_no_cat_child' ) ) ? true : false;
				$tax = get_taxonomies();
				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', co_get_option( 'group_assets_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				if ( $tax_terms ) {
					foreach ( $tax_terms as $tax_term ) { ?>
						<div class="betip line-group-assets g-row g-line group-assets-<?php echo co_get_option( 'group_assets_f' ); ?><?php echo $bg; ?>" <?php aos(); ?>>
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
											'posts_per_page'      => co_get_option( 'group_assets_n' ),
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
							<?php co_help( $text = '公司主页 → 会员商品', $number = 'group_assets_s' ); ?>
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
						<div style="text-align: center;">公司主页 → 会员商品 → 输入分类ID</div>
					</div>
					<?php co_help( $text = '公司主页 → 会员商品', $number = 'group_assets_s' ); ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if ( co_get_option( 'group_assets_get' ) == 'post' ) { ?>
		<div class="betip line-group-assets g-row g-line group-assets-<?php echo co_get_option( 'group_assets_f' ); ?>" <?php aos(); ?>>
			<div class="g-col">
				<div class="flexbox-grid">
					<div class="group-title" <?php aos_b(); ?>>
						<?php if ( ! co_get_option( 'group_assets_t' ) == '' ) { ?>
							<h3><?php echo co_get_option( 'group_assets_t' ); ?></h3>
						<?php } ?>
						<?php if ( ! co_get_option( 'group_assets_des' ) == '' ) { ?>
							<div class="group-des"><?php echo co_get_option( 'group_assets_des' ); ?></div>
						<?php } ?>
						<div class="clear"></div>
					</div>
					<?php
						$args = array(
							'post__in'  => explode( ',', co_get_option( 'group_assets_post_id' ) ),
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
							echo '<div class="be-none">公司主页 → 会员商品，输入文章ID</div>';
						endif;
						wp_reset_postdata();
					?>
					<div class="clear"></div>
					<div class="group-cat-img-more"><a href="<?php echo get_category_link( $category );?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></div>
				</div>
				<?php co_help( $text = '公司主页 → 会员商品', $number = 'group_assets_s' ); ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
<?php } ?>
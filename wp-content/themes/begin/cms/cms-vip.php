<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_vip' ) ) { ?>
	<div class="betip">
		<?php 
			$vip = ( array ) be_get_option( 'cms_vip_item' );
			if ( be_get_option( 'no_cat_top' ) ) {
				$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
				$exclude_posts = array_merge( $do_not_duplicate, $top_id );
			} else {
				$exclude_posts = '';
			}
			foreach ( $vip as $items ) { ?>

				<div class="betip cms-assets-<?php echo $items['cms_vip_f']; ?>">
					<?php if ( ! $items['cms_vip_get'] || ( $items['cms_vip_get'] == 'cat' ) ) { ?>
						<?php if ( $items['cms_vip_id'] ) { ?>
							<?php 
								$tax = get_taxonomies();

								// 将所有分类法作为参数传递给 get_terms 函数
								$tax_terms = get_terms( $tax , array(
									'include' => explode( ',', $items['cms_vip_id'] ),
									'orderby' => 'include',
									'order'   => 'ASC',
								));
								$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
								if ( $tax_terms ) {
									foreach ( $tax_terms as $tax_term ) {
								?>
									<div class="flexbox-grid">
										<h3 class="cms-picture-cat-title"><a href="<?php echo get_term_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $tax_term->name; ?></a></h3>
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
												'posts_per_page'      => $items['cms_vip_n'],
												'post__not_in'        => $exclude_posts,
												'orderby'             => 'date',
												'order'               => 'DESC',
												'ignore_sticky_posts' => 1,
											);

											$query = new WP_Query( $args );

											while ( $query->have_posts() ) : $query->the_post();
											require get_template_directory() . '/template/assets.php';
											endwhile;
											wp_reset_postdata();
										?>
										<div class="clear"></div>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<div class="cat-container ms" <?php aos_a(); ?>>
								<h3 class="cat-title">
									<?php title_i(); ?>未输入分类ID
								</h3>
								<div class="clear"></div>

								<div class="cms-cat-area">
									<ul class="cat-one-list">
										<li class="list-cat-title srm" style="padding: 15px;">首页设置 → 杂志布局 → 会员资源 → 输入分类ID</li>
									</ul>
									<div class="clear"></div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>

					<?php if ( $items['cms_vip_get'] == 'post' ) { ?>
						<?php
							$args = array(
								'post_type' => 'any',
								'post__in'  => explode( ',', $items['cms_vip_post_id'] ),
								'orderby'   => 'post__in', 
								'order'     => 'DESC',
								'ignore_sticky_posts' => true,
								);
							$query = new WP_Query( $args );
						?>
						<div class="flexbox-grid">
							<?php
								if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
									require get_template_directory() . '/template/assets.php';
								endwhile;
								else :
									echo '<div class="flex-grid-item" data-aos="fade-up"><div class="flex-grid-area grid-tip">首页设置 → 杂志布局 → 会员资源，输入文章ID</div></div>';
								endif;
								wp_reset_postdata();
							?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 会员资源', $number = 'cms_vip_s' ); ?>
	</div>
<?php } ?>
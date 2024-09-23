<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_down' ) ) { ?>
<div class="betip down-card-<?php echo be_get_option( 'cms_down_f' ); ?>">
	<?php if ( ! be_get_option( 'cms_down_get' ) || ( be_get_option( "cms_down_get" ) == 'cat' ) ) { ?>
		<?php if ( be_get_option( 'cms_down_id' ) ) { ?>
			<?php
				$cat = ( be_get_option( 'no_cat_child' ) ) ? true : false;
				if ( be_get_option( 'no_cat_top' ) ) {
					$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
					$exclude_posts = array_merge( $do_not_duplicate, $top_id );
				} else {
					$exclude_posts = '';
				}
				$btntext = be_get_option( 'cms_down_btn_text' );
				$tax = get_taxonomies();

				$tax_terms = get_terms( $tax , array(
					'include' => explode( ',', be_get_option( 'cms_down_id' ) ),
					'orderby' => 'include',
					'order'   => 'ASC',
				));

				foreach ( $tax_terms as $tax_term ) {
			?>
				<div class="flexbox-card<?php if ( be_get_option( 'cms_down_card_h' ) ) { ?> cms-down-small<?php } ?>">
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
							'posts_per_page'      => be_get_option( 'cms_down_n' ),
							'post__not_in'        => $exclude_posts,
							'orderby'             => 'date',
							'order'               => 'DESC',
							'ignore_sticky_posts' => 1,
						);

						$query = new WP_Query( $args );

						while ( $query->have_posts() ) : $query->the_post();
						if ( ! be_get_option( 'cms_down_mode' ) || ( be_get_option( "cms_down_mode" ) == 'card' ) ) {
							require get_template_directory() . '/template/down-card.php';
						}
						if ( be_get_option( 'cms_down_mode' ) == 'picture' ) {
							require get_template_directory() . '/template/down-img.php';
						}
						endwhile;
						wp_reset_postdata();
					?>
					<div class="clear"></div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="cms-cat-grid cms-cat-grid-item betip" <?php aos_a(); ?>>
				<div class="cms-cat-main tra ms">
					<h3 class="cat-grid-title"><?php title_i(); ?>未输入分类ID</h3>
					<article class="post-item-list" style="padding: 15px;">
						首页设置 → 杂志布局 → 软件下载
					</article>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if ( be_get_option( 'cms_down_get' ) == 'post' ) { ?>
		<?php if ( be_get_option( 'cms_down_post_id' ) ) { ?>
			<?php
				$args = array(
					'post_type' => 'any',
					'post__in'  => explode( ',', be_get_option( 'cms_down_post_id' ) ),
					'orderby'   => 'post__in', 
					'order'     => 'DESC',
					'ignore_sticky_posts' => true,
					);
				$query = new WP_Query( $args );
			?>
			<div class="flexbox-card">
				<?php
					$btntext = be_get_option( 'cms_down_btn_text' );
					if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
						if ( ! be_get_option( 'cms_down_mode' ) || ( be_get_option( "cms_down_mode" ) == 'card' ) ) {
							require get_template_directory() . '/template/down-card.php';
						}
						if ( be_get_option( 'cms_down_mode' ) == 'picture' ) {
							require get_template_directory() . '/template/down-img.php';
						}
					endwhile;
					else :
						echo '<div class="flex-card-item" data-aos="fade-up"><div class="flex-card-area card-tip">首页设置 → 杂志布局 → 软件下载，输入文章ID</div></div>';
					endif;
					wp_reset_postdata();
				?>
				<div class="clear"></div>
			</div>
		<?php } else { ?>
			<div class="cms-cat-grid cms-cat-grid-item betip" <?php aos_a(); ?>>
				<div class="cms-cat-main tra ms">
					<h3 class="cat-grid-title"><?php title_i(); ?>未输入文章ID</h3>
					<article class="post-item-list" style="padding: 15px;">
						首页设置 → 杂志布局 → 软件下载，输入文章ID
					</article>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
	<?php } ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 软件下载', $number = 'cms_down_s' ); ?>
</div>
<?php } ?>
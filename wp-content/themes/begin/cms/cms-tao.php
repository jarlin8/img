<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option('tao_h' ) ) { ?>
<div class="line-tao betip">
	<?php if ( be_get_option( 'tao_h_id') ) { ?>
		<?php
			$orderby = ( be_get_option( 'h_tao_sort' ) == 'views' ) ? 'meta_value' : 'date';
			$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];$exclude_posts = be_get_option( 'no_cat_top' ) ? array_merge( $do_not_duplicate,$top_id ) : '';
			$tax = get_taxonomies();
			$tax_terms = get_terms( $tax , array(
				'include' => explode( ',', be_get_option( 'tao_h_id' ) ),
				'orderby' => 'include',
				'order'   => 'ASC',
			));

			if ( $tax_terms ) {
				foreach ( $tax_terms as $tax_term ) {
					$args = array(
						'post_type' => 'any',
						'tax_query' => array(
							array(
								'taxonomy' => $tax_term->taxonomy,
								'field'    => 'term_id',
								'terms'    => $tax_term->term_id,
							),
						),

						'post_status'    => 'publish',
						'posts_per_page' => be_get_option( 'tao_h_n' ),
						'post__not_in'   => $exclude_posts,
						'meta_key'       => 'views',
						'orderby'        => $orderby, 
						'order'          => 'DESC', 
						'ignore_sticky_posts' => 1,
					);

				$be_query = new WP_Query( $args );

				if ( $be_query->have_posts() ) {
					?>
					<div class="cms-tao-box">
						<h3 class="cms-picture-cat-title"><a href="<?php echo get_category_link( $tax_term ); ?>" rel="bookmark" <?php echo goal(); ?>><?php echo $tax_term->name; ?></a></h3>
						<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
						<div class="tao-home-area tao-home-fl tao-home-fl-<?php echo be_get_option( 'cms_tao_home_f' ); ?>">
							<?php get_template_part( '/template/tao-home' ); ?>
						</div>
						<?php endwhile;wp_reset_postdata(); ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<?php cms_help( $text = '首页设置 → 杂志布局 → 商品', $number = 'tao_h_s' ); ?>
</div>
<?php } ?>
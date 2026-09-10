<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_sites' ) ) { ?>
	<div class="sites-all betip" <?php aos_a(); ?>>
		<?php if ( be_get_option( 'cms_sites_id') ) { ?>
			<?php
				$tax = array( 'favorites' );
				$tax_terms = get_terms( $tax, array( 'orderby' => 'include', 'order' => 'ASC', 'include' => explode( ',', be_get_option( 'cms_sites_id' ) ) ) );
				if ( $tax_terms ) {
					foreach ( $tax_terms as $tax_term ) {
						$args = array(
							'post_type' => array( 'sites' ),
							'tax_query' => array(
								array(
									'taxonomy' => $tax_term->taxonomy,
									'field'    => 'term_id',
									'terms'    => $tax_term->term_id,
									'include_children' => true,
								),
							),

							'post_status'    => 'publish',
							'posts_per_page' => be_get_option( 'cms_sites_n' ),
							'orderby'        => 'date',
							'order'          => 'DESC',
							'ignore_sticky_posts' => 1,
 						);
					$query = new WP_Query( $args );
				?>

					<?php if ( $query->have_posts() ) { ?>
						<div class="sites-box sites-single-related">
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
								<div class="sites-area sites-<?php echo be_get_option( 'cms_sites_fl' ); ?>">
									<?php sites_favorites(); ?>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<div class="clear"></div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 网址收藏', $number = 'cms_sites_s' ); ?>
	</div>
<?php } ?>
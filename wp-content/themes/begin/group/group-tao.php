<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'g_tao_h' ) ) { ?>
	<?php
	$tax = 'taobao';
	$tax_terms = get_terms( $tax, array( 'orderby' => 'menu_order', 'order' => 'ASC', 'include' => explode( ',',zm_get_option('g_tao_h_id' ) ) ) );
	if ( $tax_terms ) { ?>
		<?php foreach ( $tax_terms as $tax_term ) { ?>
			<?php
				if ( !zm_get_option( 'g_tao_sort' ) || ( zm_get_option( 'g_tao_sort' ) == 'time' ) ) {
					$orderby = 'date';
				}
				if ( zm_get_option( 'g_tao_sort' ) == 'views' ) {
					$orderby = 'meta_value';
				}

				$args = array(
					'post_type'        => 'tao',
					"$tax"             => $tax_term->slug,
					'post_status'      => 'publish',
					'posts_per_page'   => zm_get_option( 'g_tao_h_n' ),
					'meta_key'         => 'views',
					'orderby'          => $orderby, 
					'order'            => 'DESC', 
					'caller_get_posts' => 1
				);
				$be_query = new WP_Query( $args );
			?>
			<?php
				$args = array(
					"$tax"           => $tax_term->slug,
					'posts_per_page' => 1,
				);
				$catquery = new WP_Query( $args );
			?>
			<?php if ( $be_query->have_posts() ) { ?>
				<div class="line-tao g-row g-line sort" name="<?php echo zm_get_option( 'g_tao_s' ); ?>" <?php aos(); ?>>
					<div class="g-col">
						<div class="cms-picture-box">
							<?php while ( $catquery->have_posts() ) : $catquery->the_post(); ?>
								<div class="group-title" <?php aos_b(); ?>>
									<h3><?php echo get_the_term_list( $post->ID, 'taobao', '' ); ?></h3>
									<div class="group-des"><?php echo $tax_term->description; ?></div>
									<div class="clear"></div>
								</div>
							<?php endwhile; wp_reset_query(); ?>

							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
								<?php get_template_part( '/template/tao-home' ); ?>
							<?php endwhile;wp_reset_query(); ?>
							<div class="clear"></div>
						</div>

						<?php while ( $catquery->have_posts() ) : $catquery->the_post(); ?>
							<div class="group-post-more da">
								<a href="<?php echo get_term_link($tax_term); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="external nofollow"><i class="be be-more"></i></a>
							</div>
						<?php endwhile; wp_reset_query(); ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>
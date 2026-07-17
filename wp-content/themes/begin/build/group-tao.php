<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'g_tao_h' ) ) {
	if ( ! be_build( get_the_ID(), 'tao_bg' ) || ( be_build( get_the_ID(), 'tao_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'tao_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'tao_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
	<?php
	$tax = 'taobao';
	$tax_terms = get_terms( $tax, array( 'orderby' => 'menu_order', 'order' => 'ASC', 'include' => explode( ',',be_build( get_the_ID(),'g_tao_h_id' ) ) ) );
	if ( $tax_terms ) { ?>
		<?php foreach ( $tax_terms as $tax_term ) { ?>
			<?php
				if ( !be_build( get_the_ID(), 'g_tao_sort' ) || ( be_build( get_the_ID(), 'g_tao_sort' ) == 'time' ) ) {
					$orderby = 'date';
				}
				if ( be_build( get_the_ID(), 'g_tao_sort' ) == 'views' ) {
					$orderby = 'meta_value';
				}

				$args = array(
					'post_type'        => 'tao',
					"$tax"             => $tax_term->slug,
					'post_status'      => 'publish',
					'posts_per_page'   => be_build( get_the_ID(), 'g_tao_h_n' ),
					'meta_key'         => 'views',
					'orderby'          => $orderby, 
					'order'            => 'DESC', 
					'ignore_sticky_posts' => 1
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
				<div class="line-tao g-row g-line<?php echo $bg; ?>" <?php aos(); ?>>
					<div class="g-col">
						<div class="cms-picture-box">
							<?php while ( $catquery->have_posts() ) : $catquery->the_post(); ?>
								<div class="group-title" <?php aos_b(); ?>>
									<h3><?php echo get_the_term_list( $post->ID, 'taobao', '' ); ?></h3>
									<div class="group-des"><?php echo $tax_term->description; ?></div>
									<div class="clear"></div>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>

							<?php $build = get_the_ID(); ?>

							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
								<div class="tao-home-area tao-home-fl tao-home-fl-<?php echo be_build( $build, 'g_tao_home_f' ); ?>">
									<?php get_template_part( '/template/tao-home' ); ?>
								</div>
							<?php endwhile;wp_reset_postdata(); ?>
							<div class="clear"></div>
						</div>

						<?php while ( $catquery->have_posts() ) : $catquery->the_post(); ?>
							<div class="group-post-more">
								<a href="<?php echo get_term_link($tax_term); ?>" title="<?php _e( '更多', 'begin' ); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
						<?php bu_help( $text = '商品模块', $number = 'g_tao_s' ); ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>
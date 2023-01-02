<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('tao_h')) { ?>
<div class="line-tao sort" name="<?php echo zm_get_option('tao_h_s'); ?>">
	<?php
	$tax = 'taobao';
	$tax_terms = get_terms( $tax, array( 'orderby' => 'menu_order', 'order' => 'ASC', 'include' => explode( ',',zm_get_option('tao_h_id' ) ) ) );
	if ( $tax_terms ) { ?>
		<?php foreach ( $tax_terms as $tax_term ) { ?>
			<?php
				if ( !zm_get_option( 'h_tao_sort' ) || ( zm_get_option( 'h_tao_sort' ) == 'time' ) ) {
					$orderby = 'date';
				}
				if ( zm_get_option( 'h_tao_sort' ) == 'views' ) {
					$orderby = 'meta_value';
				}

				$args = array(
					'post_type'        => 'tao',
					"$tax"             => $tax_term->slug,
					'post_status'      => 'publish',
					'posts_per_page'   => zm_get_option( 'tao_h_n' ),
					'meta_key'         => 'views',
					'orderby'          => $orderby, 
					'order'            => 'DESC', 
					'ignore_sticky_posts' => 1
				);
				$be_query = new WP_Query( $args );
			?>
			<?php if ( $be_query->have_posts() ) { ?>
				<div class="cms-picture-box">
					<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
						<h3 class="cms-picture-cat-title"><?php echo get_the_term_list( $post->ID, 'taobao', '' ); ?></h3>
					<?php endwhile; ?>
					<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
						<?php get_template_part( '/template/tao-home' ); ?>
					<?php endwhile;wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>
<?php } ?>
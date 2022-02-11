<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_products')) { ?>
<div class="g-row g-line sort" name="<?php echo zm_get_option('group_products_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-features">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( !zm_get_option('group_products_t') == '' ) { ?>
				<h3><?php echo zm_get_option('group_products_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('group_products_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php
					$args = array(
						'post_type' => 'show',
						'showposts' => zm_get_option('group_products_n'), 
					);

					if(zm_get_option('group_products_id')) {
						$args = array(
							'showposts' => zm_get_option('group_products_n'), 
							'tax_query' => array(
								array(
									'taxonomy' => 'products',
									'terms' => explode(',',zm_get_option('group_products_id') )
								),
							)
						);
					}
				?>
				<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
				<div class="g4 g<?php echo zm_get_option('group_products_f'); ?>">
					<div class="box-4" <?php aos_b(); ?>>
						<figure class="picture-cms-img">
							<?php img_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
				<?php if ( zm_get_option('group_products_url') == '' ) { ?>
				<?php } else { ?>
					<div class="img-more"><a href="<?php echo zm_get_option('group_products_url'); ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></div>
				<?php } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
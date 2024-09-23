<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_cat_d' ) ) { ?>
<div class="g-row g-line group-cat-d sort" name="<?php echo zm_get_option( 'group_cat_d_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="grf-cat-lr">
			<div class="grf2 grfl">
				<div class="grf-cat-box">
					<ul class="grf-cat-list" <?php aos_b(); ?>>
							<?php query_posts( array( 'showposts' => zm_get_option('group_cat_d_n'), 'cat' => zm_get_option( 'group_cat_d_l_id' ) ) ); ?>
							<figure class="grf-thumbnail" <?php aos_b(); ?>>
								<h3 class="grf-cat-name" <?php aos_b(); ?>><a href="<?php echo get_category_link( zm_get_option( 'group_cat_d_l_id' ) );?>"><?php single_cat_title(); ?></a></h3>
								<img alt="contact" src="<?php echo zm_get_option( 'group_cat_d_l_img' ); ?>">
							</figure>
							<?php while ( have_posts() ) : the_post(); ?>
							<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></li>
							<?php the_title( sprintf( '<li class="list-title"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' ); ?>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
			</div>

			<div class="grf2 grfr">
				<div class="grf-cat-box">
					<ul class="grf-cat-list" <?php aos_b(); ?>>
							<?php query_posts( array( 'showposts' => zm_get_option('group_cat_d_n'), 'cat' => zm_get_option( 'group_cat_d_r_id' ) ) ); ?>
							<figure class="grf-thumbnail" <?php aos_b(); ?>>
								<h3 class="grf-cat-name" <?php aos_b(); ?>><a href="<?php echo get_category_link( zm_get_option( 'group_cat_d_r_id' ) );?>"><?php single_cat_title(); ?></a></h3>
								<img alt="contact" src="<?php echo zm_get_option( 'group_cat_d_r_img' ); ?>">
							</figure>
							<?php while ( have_posts() ) : the_post(); ?>
							<li class="list-date"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></li>
							<?php the_title( sprintf( '<li class="list-title"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></li>' ); ?>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
			</div>

			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>
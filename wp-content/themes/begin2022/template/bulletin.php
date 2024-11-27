<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="scrolldiv">
	<ul class="scrolltext placardtxt owl-carousel">
		<?php
			$args = array(
				'post_type' => 'bulletin',
				'showposts' => zm_get_option('bulletin_n'), 
			);

			if(zm_get_option('bulletin_id')) {
				$args = array(
					'showposts' => zm_get_option('bulletin_n'), 
					'tax_query' => array(
						array(
							'taxonomy' => 'notice',
							'terms' => explode(',',zm_get_option('bulletin_id') )
						),
					)
				);
			}
		?>
		<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>
			<?php the_title( sprintf( '<li class="scrolltext-title"><i class="be be-volumedown"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="scrolldiv">
	<ul class="scrolltext placardtxt owl-carousel">
		<?php
			if ( !zm_get_option('bulletin_type') || ( zm_get_option( 'bulletin_type' ) == 'category') ) {
				$post_type = 'post';
				$taxonomy = 'category';
			}

			if ( zm_get_option( 'bulletin_type' ) == 'notice' ) {
				$post_type = 'bulletin';
				$taxonomy = 'notice';
			}

			$args = array(
				'post_type' => $post_type,
				'showposts' => zm_get_option( 'bulletin_n' ),
				'ignore_sticky_posts' => 1,
			);

			if ( zm_get_option( 'bulletin_id' ) ) {
				$args = array(
					'showposts' => zm_get_option( 'bulletin_n' ), 
					'tax_query' => array(
						array(
							'taxonomy' => $taxonomy,
							'terms' => explode( ',', zm_get_option( 'bulletin_id' ) )
						),
					)
				);
			}

			$be_query = new WP_Query( $args );
		?>
		<?php if ( $be_query->have_posts() ) : ?>
			<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
			<?php the_title( sprintf( '<li class="scrolltext-title"><i class="be be-volumedown"></i><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<li class="scrolltext-title"><i class="be be-volumedown"></i>暂无公告</li>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</ul>
</div>
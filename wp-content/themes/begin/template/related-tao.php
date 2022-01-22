<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="single-goods" <?php aos_a(); ?>>
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'tao', 'orderby' => 'rand', 'posts_per_page' => zm_get_option('single_tao_n') ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>

	<div class="tl4 tm4">
		<div class="single-goods-main fd bk">
			<figure class="single-goods-img ms bk">
				<?php tao_thumbnail(); ?>
			</figure>
			<div class="single-goods-pricex">￥ <?php $price = get_post_meta($post->ID, 'pricex', true);{ echo $price; }?>元</div>
			<?php the_title( sprintf( '<h2 class="single-goods-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="clear"></div>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
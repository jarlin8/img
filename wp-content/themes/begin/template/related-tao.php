<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="single-goods" <?php aos_a(); ?>>
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'tao', 'orderby' => 'rand', 'posts_per_page' => zm_get_option( 'single_tao_n' ) ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>

	<div class="tl4 tm4">
		<div class="single-goods-main fd">
			<figure class="single-goods-img ms">
				<?php echo tao_thumbnail(); ?>
			</figure>
			<?php if ( get_post_meta( get_the_ID(), 'pricex', true ) ) { ?>
				<div class="single-goods-pricex"><?php echo get_post_meta( get_the_ID(), 'pricex', true ); ?>元</div>
			<?php } ?>
			<div class="single-goods-pricex"><?php be_vip_meta(); ?></div>
			<?php the_title( sprintf( '<h2 class="single-goods-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="clear"></div>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<div class="clear"></div>
</div>
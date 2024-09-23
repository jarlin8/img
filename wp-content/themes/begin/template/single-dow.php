<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="single-goods" <?php aos_a(); ?>>
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'download', 'orderby' => 'rand', 'posts_per_page' => 4 ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>

	<div class="tl4 tm4">
		<div class="single-goods-main">
			<figure class="single-goods-img">
				<?php echo tao_thumbnail(); ?>
			</figure>
			<div class="clear"></div>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
	<div class="clear"></div>
</div>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('products_on')) { ?>
<div class="line-four line-four-show-item sort" name="<?php echo zm_get_option('products_on_s'); ?>">
	<?php
		$args = array(
			'post_type' => 'show',
			'showposts' => zm_get_option('products_n'), 
		);

		if (zm_get_option('products_id')) {
			$args = array(
				'showposts' => zm_get_option('products_n'), 
				'tax_query' => array(
					array(
						'taxonomy' => 'products',
						'terms' => explode(',',zm_get_option('products_id') )
					),
				)
			);
		}
	?>
	<?php $be_query = new WP_Query($args); while ($be_query->have_posts()) : $be_query->the_post(); ?>

	<div class="xl4 xm4">
		<div class="picture-cms ms bk" <?php aos_a(); ?>>
			<figure class="picture-cms-img">
				<?php img_thumbnail(); ?>
				<span class="show-t"></span>
			</figure>
			<?php the_title( sprintf( '<h2 class="picture-s-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>
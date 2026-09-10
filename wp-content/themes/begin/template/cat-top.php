<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'cat_top' ) && ! is_paged() ) { ?>
	<?php
		$args = array(
			'meta_key' => 'cat_top', 
			'category__in' => array(get_query_var('cat')),
			'ignore_sticky_posts' => true, 
			);
		$query = new WP_Query( $args );
	?>
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="cat-top">
			<?php get_template_part( 'template/content', get_post_format() ); ?>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
<?php } ?>
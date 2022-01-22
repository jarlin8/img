<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="all-cat-box ms bk" <?php aos_a(); ?>>
	<div class="all-cat-h weight"><i class="be be-sort"></i><?php _e( '推荐栏目', 'begin' ); ?></div>
	<ul class="all-cat all-cat-hide">
		<?php
			$args = array(
				'exclude' => explode(',',zm_get_option('cat_all_e')),
				'hide_empty' => 0
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li class="list-cat"><a class="bk ms" href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php single_cat_title(); ?></a></li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
	<div class="clear"></div>
</div>
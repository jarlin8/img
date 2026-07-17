<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php if ( be_get_option( 'img_ajax' ) ) { ?>
	<?php grid_template_a(); ?>
	<div class="grid-main betip">
		<?php 
			$cat_ids  = be_get_option( 'img_ajax_id') ? implode( ',', be_get_option( 'img_ajax_id' ) ) : '';
			$children = ( be_get_option( 'img_ajax_cat_chil' ) && be_get_option( 'img_ajax_cat_chil') != 'false' ) ? 'true' : 'false';
			$style    = be_get_option( 'img_falls' ) ? 'falls' : 'photo';

			echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" posts_per_page="' . be_get_option( 'img_ajax_n' ) . '" column="' . be_get_option( 'img_ajax_f' ) . '" img="' . be_get_option( 'img_ajax_feature' ) . '" btn="' . be_get_option( 'img_ajax_cat_btn' ) . '" more="' . be_get_option( 'img_ajax_nav_btn' ) . '" infinite="' . be_get_option( 'img_ajax_infinite' ) . '" children="' . $children . '" " style="' . $style . '" sticky="0"]' );
		?>

		<?php be_help( $text = '首页设置 → 图片布局 → Ajax模式' ); ?>
	</div>
<?php } else { ?>
	<?php if ( be_get_option( 'grid_fall' ) ) { ?>
		<?php grid_template_a(); ?>
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$notcat = be_get_option( 'grid_not_cat' ) ? implode( ',', be_get_option( 'grid_not_cat' ) ) : '';
			$top_id = be_get_option( 'img_top' ) ? explode( ',', be_get_option( 'img_top_id' ) ) : '';

			$args = array(
				'category__not_in'    => explode( ',',$notcat ),
				'post__not_in'        => $top_id,
				'ignore_sticky_posts' => 0, 
				'paged'               => $paged
			);
			query_posts( $args );
		?>
		<?php fall_main(); ?>
	<?php } else { ?>
		<?php grid_template_b(); ?>
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$notcat = be_get_option( 'grid_not_cat' ) ? implode( ',', be_get_option( 'grid_not_cat' ) ) : '';
			$top_id = be_get_option( 'img_top' ) ? explode( ',', be_get_option( 'img_top_id' ) ) : '';

			$args = array(
				'category__not_in'    => explode( ',',$notcat ),
				'post__not_in'        => $top_id,
				'ignore_sticky_posts' => 0, 
				'paged'               => $paged
			);
			query_posts( $args );
		?>
		<?php grid_template_c(); ?>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>
<?php 
/*
Template Name: 图片布局
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php if ( zm_get_option( 'img_ajax' ) ) { ?>
	<?php grid_template_a(); ?>
	<?php 
		if ( zm_get_option( 'img_ajax_id' ) ) {
			$cat_ids = implode( ',', zm_get_option( 'img_ajax_id' ) );
		} else {
			$cat_ids = '';
		}
		echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" posts_per_page="' . zm_get_option( 'img_ajax_n' ) . '" column="' . zm_get_option( 'img_ajax_f' ) . '" img="' . zm_get_option( 'img_ajax_feature' ) . '" btn="' . zm_get_option( 'img_ajax_cat_btn' ) . '" more="' . zm_get_option( 'img_ajax_nav_btn' ) . '" infinite="' . zm_get_option( 'img_ajax_infinite' ) . '"]' );
	?>
<?php } else { ?>
	<?php if (zm_get_option('grid_fall')) { ?>
		<?php grid_template_a(); ?>
		<?php if ( zm_get_option( 'order_btu' ) && ! is_paged() && is_front_page() ) { ?><?php be_order_btu(); ?><?php } ?>
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			if ( zm_get_option( 'grid_not_cat' ) ) {
				$notcat = implode( ',', zm_get_option( 'grid_not_cat' ) );
			} else {
				$notcat = '';
			}
			$args = array(
				'category__not_in' => explode( ',',$notcat ),
				'ignore_sticky_posts' => 0, 
				'paged' => $paged
			);
			query_posts( $args );
			if ( zm_get_option( 'order_btu' ) ) {
				be_order();
			}
		?>
		<?php fall_main(); ?>
	<?php } else { ?>
		<?php grid_template_b(); ?>
		<?php if ( zm_get_option( 'order_btu' ) && ! is_paged() ) { ?><?php be_order_btu();?><?php } ?>
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			if ( zm_get_option( 'grid_not_cat' ) ) {
				$notcat = implode( ',', zm_get_option( 'grid_not_cat' ) );
			} else {
				$notcat = '';
			}
			$args = array(
				'category__not_in' => explode( ',',$notcat ),
				'ignore_sticky_posts' => 0, 
				'paged' => $paged
			);
			query_posts( $args );

			if ( is_front_page() ){
				if ( zm_get_option( 'order_btu' ) ) {
					be_order();
				}
			}
		?>
		<?php grid_template_c(); ?>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>
<?php 
/*
Template Name: 图片布局
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php if (zm_get_option('grid_fall')) { ?>
	<?php grid_template_a(); ?>
	<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$notcat = explode(',',zm_get_option('not_cat_n'));
		$args = array(
			'category__not_in' => $notcat,
		    'ignore_sticky_posts' => 0, 
			'paged' => $paged
		);
		query_posts( $args );
	?>
	<?php fall_main(); ?>
<?php } else { ?>
	<?php grid_template_b(); ?>
	<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$notcat = explode(',',zm_get_option('not_cat_n'));
		$args = array(
			'category__not_in' => $notcat,
		    'ignore_sticky_posts' => 0, 
			'paged' => $paged
		);
		query_posts( $args );
	?>
	<?php grid_template_c(); ?>
<?php } ?>
<?php get_footer(); ?>
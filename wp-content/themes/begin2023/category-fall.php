<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 瀑布流
 */
get_header(); ?>

<?php if ((zm_get_option('no_child')) && is_category() ) { ?>
	<?php 
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		query_posts(array('category__in' => array(get_query_var('cat')), 'paged' => $paged,));
	?>
<?php } ?>

<?php if ( zm_get_option( 'order_btu' ) ) { ?><?php be_order(); ?><?php } ?>

<?php fall_main(); ?>
<?php get_footer(); ?>
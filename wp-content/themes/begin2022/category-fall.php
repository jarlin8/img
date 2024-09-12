<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<?php if ((zm_get_option('no_child')) && is_category() ) { ?>
	<?php 
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		query_posts(array('category__in' => array(get_query_var('cat')), 'paged' => $paged,));
	?>
<?php } ?>
<?php fall_main(); ?>
<?php get_footer(); ?>
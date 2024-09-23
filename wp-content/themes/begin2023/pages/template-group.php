<?php
/*
Template Name: 公司主页
*/
if ( ! defined( 'ABSPATH' ) ) exit;
add_filter('body_class','group_body');
?>
<?php get_header(); ?>
<?php group_template(); ?>
<?php get_footer(); ?>
<?php
/*
Template Name: 公司主页
*/
if ( ! defined( 'ABSPATH' ) ) exit;
add_filter('body_class','group_body');
get_header();
group_template();
get_footer();
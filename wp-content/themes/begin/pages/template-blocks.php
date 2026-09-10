<?php
/*
Template Name: 模块构建
Template Post Type: post, page
*/

if ( ! defined( 'ABSPATH' ) ) exit;
add_filter('body_class','group_body');
get_header();
build_template();
get_footer();
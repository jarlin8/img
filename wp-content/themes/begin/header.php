<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0<?php if ( zm_get_option( 'mobile_viewport' ) ) { ?>, maximum-scale=1.0, maximum-scale=0.0, user-scalable=no<?php } ?>">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php do_action( 'title_head' ); ?>
<?php do_action( 'favicon_ico' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php do_action( 'head_other' ); ?>

</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site<?php page_class(); ?><?php if ( zm_get_option( 'body_width' ) == 'percent' ) { ?> percent<?php } ?><?php if ( zm_get_option( 'be_debug' ) ) { ?> debug<?php } ?><?php if ( ! zm_get_option( 'turn_small' ) ) { ?> nav-normal<?php } ?>">
<?php get_template_part( 'template/menu', 'index' ); ?>
<?php do_action( 'be_head' ); ?>
<?php do_action( 'be_header_sub' ); ?>
<div id="content" class="site-content<?php decide_h(); ?>">
<?php like_left(); ?>
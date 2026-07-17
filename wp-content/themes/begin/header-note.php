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
<body <?php body_class( 'note' ); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page-note" class="page-note site<?php page_class(); ?><?php if ( zm_get_option( 'be_debug' ) ) { ?> debug<?php } ?>">
<?php get_template_part( 'template/menu', 'index' ); ?>
	<div id="content-note" class="content-note site-content">
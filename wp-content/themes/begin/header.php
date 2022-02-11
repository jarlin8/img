<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1<?php if ( zm_get_option('mobile_viewport')) { ?>, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no<?php } ?>" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php be_title(); ?>
<?php if ( !zm_get_option('favicon') == '' ) { ?>
<link rel="shortcut icon" href="<?php echo zm_get_option( 'favicon' ); ?>">
<?php } ?>
<?php if ( !zm_get_option('apple_icon') == '' ) { ?>
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo zm_get_option( 'apple_icon' ); ?>" />
<?php } ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php echo zm_get_option('ad_t'); ?>
<?php echo zm_get_option('tongji_h'); ?>
<?php if ( wp_is_mobile() && is_home() && !zm_get_option( 'mobile_home_url' ) == '' ) { ?>
	<?php header('location:'.zm_get_option( 'mobile_home_url' ).'') ?>
<?php } ?>

</head>
<body <?php body_class(); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page" class="hfeed site<?php page_class(); ?>">
	<?php get_template_part( 'template/menu', 'index' ); ?>
	<?php if ( zm_get_option( 'm_nav' ) ) { ?>
		<?php if ( wp_is_mobile() ) { ?><?php get_template_part( 'inc/menu-m' ); ?><?php } ?>
	<?php } ?>
	<nav class="bread">
		<div class="be-bread">
			<?php be_breadcrumbs(); ?>
		</div>
	</nav>
	<?php if ( zm_get_option( 'h_widget_m' ) == 'all_m' ) { ?>
		<?php top_widget(); ?>
	<?php } ?>
	<?php get_template_part( 'ad/ads', 'header' ); ?>
	<?php get_template_part( 'template/header-sub' ); ?>
	<?php get_template_part( 'template/header-slider' ); ?>
	<div id="content" class="site-content<?php decide_h(); ?>">
	<?php like_left(); ?>
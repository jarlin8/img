<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1<?php if ( zm_get_option('mobile_viewport')) { ?>, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no<?php } ?>" />
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php do_action( 'title_head' ); ?>
<?php do_action( 'favicon_ico' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php do_action( 'head_other' ); ?>

</head>
<body <?php body_class(); ?> ontouchstart="">
<?php wp_body_open(); ?>
<div id="page" class="hfeed site<?php page_class(); ?>">
	<?php get_template_part( 'template/menu', 'index' ); ?>
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
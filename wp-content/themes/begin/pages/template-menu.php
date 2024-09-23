<?php
/*
Template Name: 菜单页面
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.menu-list .widget {
	padding: 0 !important;
	background: transparent;
	border-radius: 0;
	border: none;
	box-shadow: none;
}

.menu-list .widget ul {
	padding: 0;
}

.menu-list .widget-title {
	font-size: 20px;
	font-size: 2rem;
	line-height: 30px;
	text-align: center;
	margin: 10px 0;
	display: flex;
	justify-content: center;
	align-items: center;
}

.menu-list .menu {
	float: left;
	width: 100%;
}

.menu-list {
	margin: 0 -2px;
}

.menu-list .menu li {
	float: left;
	width: 14.285714%;
	line-height: 280%;
	padding: 3px;
}

.menu-list .menu li a {
	background: var(--be-bg-white);
	display: block;
	text-align: center;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
	padding: 0 2px;
	border: 1px solid #e7e7e7;
	border-radius: 8px;
	transition-duration: .5s;
}

@media screen and (max-width: 1080px) {
	.menu-list .menu li {
		width: 20%;
	}
}

@media screen and (max-width: 800px) {
	.menu-list .menu li {
		width: 25%;
	}

	.menu-list .menu li {
		margin: 0 -1px -1px 0;
	}
}

@media screen and (max-width: 700px) {
	.menu-list .menu li {
		width: 33.3333333333%;
	}
}

@media screen and (max-width: 500px) {
	.menu-list .menu li {
		width: 50%;
	}
}

.menu-list a, #links a {
	-webkit-transition: -webkit-transform 0.2s;
	transition: transform 0.2s;
}

.menu-list a:hover {
	-webkit-transform: scale(0.9);
	transform: scale(0.9);
}

.menu-list .add-widgets {
	background: #fff;
	text-align: center;
	margin: 0 0 10px;
	padding: 30px;
	border: 1px solid #e7e7e7;
}

.night .menu-list .menu li a {
	border: 1px solid #262626 !important;
}

.night .menu-list .widget-title {
	border: none !important;
}
</style>

<div id="primary" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<div class="menu-list">
			<?php if ( ! dynamic_sidebar( 'all-cat' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“菜单页面”添加自定义菜单小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
		</div>
	</main>
</div>

<?php get_footer(); ?>
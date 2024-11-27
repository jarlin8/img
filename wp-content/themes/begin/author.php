<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
// 个人主页
get_header(); ?>

<div class="ajax-content-area content-area">
	<main id="main" class="site-main ajax-site-main" role="main">
		<?php if ( ! zm_get_option( 'user_inf_page' ) || ( zm_get_option( "user_inf_page" ) == 'grid' ) ) { ?>
			<?php echo do_shortcode( '[be_ajax_post posts_per_page="21" column="3" style="grid" btn="no" btn_all= "no" more="more"]' ); ?>
		<?php } ?>
		<?php if ( zm_get_option( 'user_inf_page' ) == 'img' ) { ?>
			<?php echo do_shortcode( '[be_ajax_post posts_per_page="25" column="5" img="0" btn="no" btn_all= "no" more="more"]' ); ?>
		<?php } ?>
	</main>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>
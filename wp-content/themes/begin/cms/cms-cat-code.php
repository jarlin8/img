<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_ajax_cat' ) ) { ?>
	<div class="cms-ajax-cat-post betip">
		<?php echo do_shortcode( be_get_option( 'cms_ajax_cat_post_code' ) ); ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → Ajax分类短代码', $number = 'cms_ajax_cat_post_s' ); ?>
	</div>
<?php } ?>
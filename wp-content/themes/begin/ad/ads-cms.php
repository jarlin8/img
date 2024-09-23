<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('ad_a')) { ?>
<div class="tg-cms betip<?php if (zm_get_option('post_no_margin') && zm_get_option('news_model') == 'news_normal') { ?> upclose<?php } ?>" <?php aos_a(); ?>>
	<?php if ( wp_is_mobile() ) { ?>
		<?php if ( zm_get_option('ad_a_c_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_a_c_m') ); ?></div><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_a_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_a_c') ); ?></div><?php } ?>
	<?php } ?>
	<?php be_help( $text = '主题选项 → 广告位 → 文章列表广告位' ); ?>
</div>
<?php } ?>
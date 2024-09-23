<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('ad_a')) { ?>
<?php if ($wp_query->current_post == 1) : ?>
	<div class="tg-box betip<?php if (zm_get_option('post_no_margin')) { ?> upclose<?php } ?>" <?php aos_a(); ?>>
		<?php if ( wp_is_mobile() ) { ?>
			 <?php if ( zm_get_option('ad_a_c_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_a_c_m') ); ?></div><?php } ?>
		<?php } else { ?>
			 <?php if ( zm_get_option('ad_a_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_a_c') ); ?></div><?php } ?>
		<?php } ?>
		<?php be_help( $text = '主题选项 → 广告位 → 文章列表广告位' ); ?>
	</div>
<?php endif; ?>
<?php } ?>
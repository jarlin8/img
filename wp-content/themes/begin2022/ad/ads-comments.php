<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('ad_c')) { ?>
<div class="tg-box" <?php aos_a(); ?>>
	<?php if ( wp_is_mobile() ) { ?>
		<?php if ( zm_get_option('ad_c_c_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_c_c_m') ); ?></div><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_c_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_c_c') ); ?></div><?php } ?>
	<?php } ?>
</div>
<?php } ?>
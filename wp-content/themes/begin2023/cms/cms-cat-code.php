<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'cms_ajax_cat' ) ) { ?>
	<div class="cms-ajax-cat-post sort" name="<?php echo zm_get_option( 'cms_ajax_cat_post_s' ); ?>">
		<?php echo do_shortcode( zm_get_option( 'cms_ajax_cat_post_code' ) ); ?>
	</div>
<?php } ?>
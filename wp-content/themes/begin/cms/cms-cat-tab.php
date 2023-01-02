<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_cat_tab')) { ?>
<div class="cms-cat-tab-box sort" name="<?php echo zm_get_option('cms_cat_tab_s'); ?>" <?php aos_a(); ?>>
	<div class="cms-cat-tab bk ms">
		<?php echo do_shortcode( '[be_ajax_post style="imglist" terms="' . zm_get_option( 'cms_cat_tab_id' ) . '" posts_per_page="' . zm_get_option( 'cms_cat_tab_n' ) . '" listimg="yes" btn_all="no"]' ); ?>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>
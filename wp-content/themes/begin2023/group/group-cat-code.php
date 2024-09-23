<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_ajax_cat' ) ) { ?>
	<div class="g-row g-line group-ajax-cat-post sort" name="<?php echo zm_get_option( 'group_ajax_cat_post_s' ); ?>">
		<div class="g-col">
			<?php echo do_shortcode( zm_get_option( 'group_ajax_cat_post_code' ) ); ?>
		</div>
	</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'h_cat_cover' ) ) { ?>
	<div class="sort" name="<?php echo zm_get_option( 'cms_cover_s' ); ?>">
		<?php cat_cover(); ?>
	</div>
<?php } ?>
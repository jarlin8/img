<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('letter_show')){ ?>
<div class="filter-box sort ms bk" name="<?php echo zm_get_option('letter_show_s'); ?>" <?php aos_a(); ?>>
	<div class="letter-t"><i class="be be-sort"></i><span><?php echo zm_get_option('letter_t'); ?></span></div>
		<?php if (!zm_get_option('letter_hidden')) { ?><div class="letter-box-main letter-box-main-h"><?php } else { ?><div class="letter-box-main"><?php } ?>
			<?php specs_show(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('filters') && zm_get_option('filter_general')){ ?>
<div class="filter-box da ms bk" <?php aos_a(); ?>>
	<div class="filter-t weight"><i class="be be-sort"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
		<?php if (zm_get_option('filters_hidden')) { ?><div class="filter-box-main filter-box-main-h"><?php } else { ?><div class="filter-box-main"><?php } ?>
		<?php require get_template_directory() . '/inc/filter-core.php'; ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
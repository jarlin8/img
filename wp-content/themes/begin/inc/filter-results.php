<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="filter-box ms" <?php aos_a(); ?>>
	<div class="filter-t weight"><i class="dashicons dashicons-image-filter"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
		<div class="filter-box-main">
		<?php require get_template_directory() . '/inc/filter-core.php'; ?>
		<div class="clear"></div>
	</div>
</div>
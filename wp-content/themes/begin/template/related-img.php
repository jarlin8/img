<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option('related_mode' ) == 'slider_grid' ) { ?>
<div id="related-img" class="ms" <?php aos_a(); ?>>
<?php } ?>
<?php if ( ! zm_get_option( 'related_mode' ) || ( zm_get_option( 'related_mode' ) == 'related_normal' ) ) { ?>
<div class="related-article">
<?php } ?>
	<?php related_core(); ?>
	<div class="clear"></div>
</div>
<?php if ( zm_get_option( 'post_no_margin' ) || zm_get_option( 'news_model' )) { ?><div class="domargin"></div><?php } ?>
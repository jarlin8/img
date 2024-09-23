<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'h_cat_cover' ) ) { ?>
	<div class="betip">
		<?php cat_cover(); ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 分类封面', $number = 'cms_cover_s' ); ?>
	</div>
<?php } ?>
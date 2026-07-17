<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_ajax_items_b' ) ) { ?>
	<div class="cms-new-code betip">
		<?php 
			$tabs = ( array ) be_get_option( 'cms_ajax_item_b' );
			$top  = be_get_option( 'no_cat_top' ) ? '1' : '0';
			foreach ( $tabs as $items ) {
				if ( ! empty( $items['cms_ajax_item_b_id'] ) ) {
					echo do_shortcode( '[be_ajax_post style="' . $items['cms_ajax_item_b_mode'] . '" terms="' . $items['cms_ajax_item_b_id'] . '" posts_per_page="' . $items['cms_ajax_item_b_n'] . '" column="' . $items['cms_ajax_item_b_f'] . '" children="' . $items['cms_ajax_item_b_chil'] . '" more="' . $items['cms_ajax_item_b_nav_btn'] . '" btn="' . $items['cms_ajax_item_b_btn'] . '" btn_all="no" top="' .$top . '"]' );
				}
			}
		?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 分类模块B', $number = 'cms_ajax_item_b_s' ); ?>
	</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="clear"></div>
<div class="begin-tabs-content" <?php aos_a(); ?>>
	<?php 
		if ( ! be_get_option( 'tabs_mode' ) || ( be_get_option( 'tabs_mode' ) == 'imglist' ) ) {
			$style = 'imglist';
		}
		if ( be_get_option( 'tabs_mode' ) == 'grid' ) {
			$style = 'grid';
		}
		if ( be_get_option( 'tabs_mode' ) == 'default' ) {
			$style = 'default';
		}
		if ( be_get_option( 'tabs_mode' ) == 'photo' ) {
			$style = 'photo';
		}
		if ( ! be_get_option( 'home_tab_cat_chil' ) || ( be_get_option( 'home_tab_cat_chil' ) == 'true' ) ) {
			$children = 'true';
		}
		if ( be_get_option( 'home_tab_cat_chil' ) == 'false' ) {
			$children = 'false';
		}
		$top = be_get_option( 'no_cat_top' ) ? '1' : '0';
		echo do_shortcode( '[be_ajax_post style="' . $style .'" terms="' . be_get_option( 'home_tab_cat_id' ) . '" posts_per_page="' . be_get_option( 'tab_b_n' ) . '" column="' . be_get_option( 'home_tab_code_f' ) . '" children="' . $children . '" mid="1" btn_all="no" top="' .$top . '"]' );
	?>
	<div class="clear"></div>
</div>
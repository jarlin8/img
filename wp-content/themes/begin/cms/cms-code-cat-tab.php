<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="clear"></div>
<div class="begin-tabs-content" <?php aos_a(); ?>>
	<?php 
		if ( ! zm_get_option( 'tabs_mode' ) || ( zm_get_option( 'tabs_mode' ) == 'imglist' ) ) {
			$style = 'imglist';
		}
		if ( zm_get_option( 'tabs_mode' ) == 'grid' ) {
			$style = 'grid';
		}
		if ( zm_get_option( 'tabs_mode' ) == 'default' ) {
			$style = 'default';
		}
		if ( zm_get_option( 'tabs_mode' ) == 'photo' ) {
			$style = 'photo';
		}
		echo do_shortcode( '[be_ajax_post style="' . $style .'" terms="' . zm_get_option( 'home_tab_cat_id' ) . '" posts_per_page="' . zm_get_option( 'tab_b_n' ) . '" column="' . zm_get_option( 'home_tab_code_f' ) . '" btn_all="no"]' );
	?>
	<div class="clear"></div>
</div>
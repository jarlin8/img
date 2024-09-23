<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_tab' ) ) {
	if ( ! co_get_option( 'tab_bg' ) || ( co_get_option( 'tab_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'tab_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'tab_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<?php $hm = (!co_get_option('group_tab_title_m') || co_get_option('group_tab_title_m') == 'yes') ? ' group-tabs-show' : ''; ?>
<div class="g-row g-line group-tabs-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-tabs-content<?php if ( co_get_option( 'group_tab_title_h' ) ) { ?> group-tabs-fold<?php echo $hm; ?><?php } ?><?php if ( ! co_get_option( 'group_tab_img_meta' ) ) { ?> group-tab-img-meta<?php } ?><?php if ( co_get_option( 'group_tab_title_c' ) ) { ?> group-tab-title-c<?php } ?>">
			<?php if ( ! co_get_option('group_tab_t') == '' ) { ?>
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! co_get_option('group_tab_t') == '' ) { ?>
						<h3><?php echo co_get_option('group_tab_t'); ?></h3>
					<?php } ?>
					<?php if ( ! co_get_option('group_tab_des') == '' ) { ?>
						<div class="group-des group-tab-des"><?php echo co_get_option('group_tab_des'); ?></div>
					<?php } ?>
					<div class="clear"></div>
				</div>
			<?php } ?>

			<?php 
				$tabs = ( array ) co_get_option( 'group_tab_items' );
				foreach ( $tabs as $items ) {
					if ( ! empty( $items['group_tab_cat_id'] ) ) {
						if ( isset( $items['group_tab_cat_btn'] ) && $items['group_tab_cat_btn'] == 'no' ) {
							echo '<div class="group-margin-btn"></div>';
						}
	
						if ( isset( $items['group_tab_cat_btn'] ) ) {
						$btn = $items['group_tab_cat_btn'];
						} else {
						$btn = '';
						}
						echo do_shortcode( '[be_ajax_post style="' . $items['group_tabs_mode'] . '" terms="' . $items['group_tab_cat_id'] . '" posts_per_page="' . $items['group_tab_n'] . '" column="' . $items['group_tabs_f'] . '" children="' . $items['group_tab_cat_chil'] . '" more="' . $items['group_tabs_nav_btn'] . '" btn="' . $btn . '" btn_all="no" boxs=""]' );
					}
				}
			?>

			<div class="clear"></div>
		</div>
		<?php co_help( $text = '公司主页 → AJAX分类', $number = 'group_tab_s' ); ?>
	</div>
</div>
<?php } ?>
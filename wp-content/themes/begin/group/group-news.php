<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_new' ) ) {
	if ( ! co_get_option( 'new_bg' ) || ( co_get_option( 'new_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'new_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'new_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-news-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-news">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option('group_new_t') == '' ) { ?>
					<h3><?php echo co_get_option('group_new_t'); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_new_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option('group_new_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<?php 
				if ( co_get_option( 'group_new_code_id' ) ) {
					$cat_ids = implode( ',', co_get_option( 'group_new_code_id' ) );
				} else {
					$cat_ids = '';
				}
				if ( ! co_get_option( 'group_new_code_cat_chil' ) || ( co_get_option( 'group_new_code_cat_chil' ) == 'true' ) ) {
					$children = 'true';
				}
				if ( co_get_option( 'group_new_code_cat_chil' ) == 'false' ) {
					$children = 'false';
				}
				echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" column="' . co_get_option( 'group_new_code_f' ) . '" posts_per_page="' . co_get_option( 'group_new_code_n' ) . '" style="' . co_get_option( 'group_new_code_style' ) . '" btn="' . co_get_option( 'group_new_code_no_cat_btn' ) . '" more="' . co_get_option( 'group_new_more' ) . '" children="' . $children . '" sticky="0"]' );
			?>
		</div>
		<?php co_help( $text = '公司主页 → 最新文章', $number = 'group_new_s' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_new' ) ) {
	if ( ! be_build( get_the_ID(), 'new_bg' ) || ( be_build( get_the_ID(), 'new_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'new_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'new_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-news-line<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-news">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(),'group_new_t') == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(),'group_new_t'); ?></h3>
				<?php } ?>
				<?php if ( ! be_build( get_the_ID(),'group_new_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(),'group_new_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<?php 
				if ( be_build( get_the_ID(), 'group_new_code_id' ) ) {
					$cat_ids = implode( ',', be_build( get_the_ID(), 'group_new_code_id' ) );
				} else {
					$cat_ids = '';
				}
				if ( ! be_build( get_the_ID(), 'group_new_code_cat_chil' ) || ( be_build( get_the_ID(), 'group_new_code_cat_chil' ) == 'true' ) ) {
					$children = 'true';
				}
				if ( be_build( get_the_ID(), 'group_new_code_cat_chil' ) == 'false' ) {
					$children = 'false';
				}
				echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" column="' . be_build( get_the_ID(), 'group_new_code_f' ) . '" posts_per_page="' . be_build( get_the_ID(), 'group_new_code_n' ) . '" style="' . be_build( get_the_ID(), 'group_new_code_style' ) . '" btn="' . be_build( get_the_ID(), 'group_new_code_no_cat_btn' ) . '" more="' . be_build( get_the_ID(), 'group_new_more' ) . '" children="' . $children . '" sticky="0"]' );
			?>
		</div>
		<?php bu_help( $text = '最新文章', $number = 'group_new_s' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
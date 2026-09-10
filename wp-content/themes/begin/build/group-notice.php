<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_build( get_the_ID(), 'group_notice' ) ) {
	if ( ! be_build( get_the_ID(), 'notice_bg' ) || ( be_build( get_the_ID(), 'notice_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( be_build( get_the_ID(), 'notice_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( be_build( get_the_ID(), 'notice_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-notice<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col ">
		<div class="section-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! be_build( get_the_ID(), 'group_notice_t' ) == '' ) { ?>
					<h3><?php echo be_build( get_the_ID(), 'group_notice_t' ); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_notice_des') == '' ) { ?>
					<div class="group-des"><?php echo be_build( get_the_ID(), 'group_notice_des' ); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-notice-wrap">
				<div class="group-notice-img">
					<div class="group-notice-bg tup" <?php aos_b(); ?>>
						<img src="<?php echo be_build( get_the_ID(), 'group_notice_img' ); ?>" alt="<?php echo be_build( get_the_ID(), 'group_notice_t'); ?>">
					</div>
				</div>

				<div class="group-notice-inf single-content sanitize" <?php aos_f(); ?>>
					<div class="text-back be-text"><?php echo wpautop( be_build( get_the_ID(), 'group_notice_inf' ) ); ?></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php bu_help( $text = '公示板', $number = 'group_notice_s' ); ?>
	</div>
</div>
<?php } ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( co_get_option( 'group_notice' ) ) {
	if ( ! co_get_option( 'notice_bg' ) || ( co_get_option( 'notice_bg' ) == 'auto' ) ) {
		$bg = '';
	}
	if ( co_get_option( 'notice_bg' ) == 'white' ) {
		$bg = ' group-white';
	}
	if ( co_get_option( 'notice_bg' ) == 'gray' ) {
		$bg = ' group-gray';
	}
?>
<div class="g-row g-line group-notice<?php echo $bg; ?>" <?php aos(); ?>>
	<div class="g-col ">
		<div class="section-box">
			<div class="group-title" <?php aos_b(); ?>>
				<?php if ( ! co_get_option('group_notice_t') == '' ) { ?>
					<h3><?php echo co_get_option('group_notice_t'); ?></h3>
				<?php } ?>
				<?php if ( ! co_get_option('group_notice_des') == '' ) { ?>
					<div class="group-des"><?php echo co_get_option('group_notice_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-notice-wrap">
				<div class="group-notice-img">
					<div class="group-notice-bg tup" <?php aos_b(); ?>>
						<img src="<?php echo co_get_option( 'group_notice_img' ); ?>" alt="<?php echo co_get_option('group_notice_t'); ?>">
					</div>
				</div>

				<div class="group-notice-inf single-content sanitize" <?php aos_f(); ?>>
					<div class="text-back be-text"><?php echo wpautop( co_get_option( 'group_notice_inf' ) ); ?></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php co_help( $text = '公司主页 → 公示板', $number = 'group_notice_s' ); ?>
	</div>
</div>
<?php } ?>
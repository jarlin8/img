<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_notice')) { ?>
<div class="g-row g-line group-notice sort" name="<?php echo zm_get_option('group_notice_s'); ?>" <?php aos(); ?>>
	<div class="g-col ">
		<div class="section-box">
			<div class="group-title bgt" <?php aos_b(); ?>>
				<?php if ( ! zm_get_option('group_notice_t') == '' ) { ?>
					<h3 class="bgt"><?php echo zm_get_option('group_notice_t'); ?></h3>
				<?php } ?>
				<?php if ( ! zm_get_option('group_notice_des') == '' ) { ?>
					<div class="group-des bgt"><?php echo zm_get_option('group_notice_des'); ?></div>
				<?php } ?>
				<div class="clear"></div>
			</div>

			<div class="group-notice-wrap">
				<div class="group-notice-img">
					<div class="group-notice-bg" <?php aos_g(); ?>>
						<img alt="notice" src="<?php echo zm_get_option( 'group_notice_img' ); ?>">
					</div>
				</div>

				<div class="group-notice-inf bgt" <?php aos_b(); ?>>
					<?php echo zm_get_option('group_notice_inf'); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
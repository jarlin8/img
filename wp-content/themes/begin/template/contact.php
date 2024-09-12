<?php if (zm_get_option('contact_us')) { ?>
<div class="contactus<?php if ( wp_is_mobile() ) { ?> contactus-m<?php } else { ?> contactus-p<?php } ?>">
	<div class="usbtn dah"></div>
	<div class="usmain-box">
		<div class="usmain">
			<?php if ( zm_get_option('weixing_us')) { ?>
				<div class="usbox usweixin">
					<img title="微信咨询" alt="微信" src="<?php echo zm_get_option('weixing_us'); ?>"/>
					<p><i class="be be-weixin"></i><?php echo zm_get_option('weixing_us_t'); ?></p>
				</div>
			<?php } ?>

			<?php if ( !zm_get_option('usqq_id') == '' ) { ?>
				<div class="usbox usqq">
					<p><a href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo zm_get_option('usqq_id'); ?>&site=qq&menu=yes" title="QQ咨询" target="_blank" rel="external nofollow" ><i class="be be-qq"></i><?php echo zm_get_option('usqq_t'); ?></a></p>
				</div>
			<?php } ?>

			<?php if ( !zm_get_option('usshang_url') == '' ) { ?>
				<div class="usbox usshang">
					<p><a target="_blank" rel="external nofollow" href="<?php echo zm_get_option('usshang_url'); ?>"><i class="be be-timerauto"></i><?php echo zm_get_option('usshang_t'); ?></a></p>
				</div>
			<?php } ?>

			<?php if ( !zm_get_option('us_phone') == '' ) { ?>
				<?php if ( wp_is_mobile() ) { ?>
					<div class="usbox usphone-m">
						<p><a target="_blank" rel="external nofollow" href="tel:<?php echo zm_get_option('us_phone'); ?>"><i class="be be-phone"></i><?php echo zm_get_option('us_phone_t'); ?></a></p>
					</div>
				<?php } else { ?>
					<div class="usbox usphone">
						<p><i class="be be-phone ustel"></i><?php echo zm_get_option('us_phone'); ?></p>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>
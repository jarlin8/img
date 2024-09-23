<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( wp_is_mobile() ) { ?><?php if ( zm_get_option('m_phone') == '' ) { ?><?php } else { ?><li class="phone-mobile"><a class="fo" target="_blank" rel="external nofollow" href="tel:<?php echo zm_get_option('m_phone'); ?>"><i class="be be-phone"></i></a></li><?php } ?><?php } ?>
<li class="qqonline">
	<?php if ( wp_is_mobile() ) { ?>
		<a class="qq-mobile fo" href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo zm_get_option('qq_id'); ?>&site=qq&menu=yes" onClick="copyUrlqq()" target="_blank" rel="external nofollow"><i class="be be-qq"></i></a>
		<textarea cols="20" rows="10" id="qq-id" class="da"><?php echo zm_get_option('qq_id'); ?></textarea>
		<script type="text/javascript">function copyUrlqq() {var Urlqq=document.getElementById("qq-id");Urlqq.select();document.execCommand("Copy");alert("QQ号已复制，可粘贴到QQ中添加我为好友！");}</script>
	<?php } else { ?>
	<div class="online bgt">
		<a class="ms fo"><i class="be be-qq"></i></a>
	</div>
	<div class="qqonline-box qq-b bgt">
		<div class="qqonline-main yy bk">
			<div class="tcb-qq"><div></div><div></div><div></div><div></div><div></div></div>
			<?php if ( !zm_get_option('qq_name') == '' ) { ?><h4 class="qq-name"><?php echo zm_get_option('qq_name'); ?></h4><?php } ?>

			<?php if ( !zm_get_option('m_phone') == '' ) { ?>
				<div class="nline-phone">
					<i class="be be-phone"></i><?php echo zm_get_option('m_phone'); ?>
				</div>
			<?php } ?>

			<?php if ( !zm_get_option('qq_id') == '' ) { ?>
			<div class="nline-qq">
				<div class="qq-wpa qq-wpa-go">
					<textarea cols="1" rows="1" id="qq-id" class="da"><?php echo zm_get_option('qq_id'); ?></textarea>
					<script type="text/javascript">function copyUrlqq() {var Urlqq=document.getElementById("qq-id");Urlqq.select();document.execCommand("Copy");alert("QQ号已复制，可粘贴到QQ中添加我为好友！");}</script>
					<a href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo zm_get_option('qq_id'); ?>&site=qq&menu=yes" onClick="copyUrlqq()" title="QQ在线咨询" target="_blank" rel="external nofollow"><i class="be be-qq ms"></i><span class="qq-wpa-t">QQ在线咨询</span></a>
				</div>
			</div>
			<?php } ?>

			<?php if ( zm_get_option('weixing_qr')) { ?>
				<div class="nline-wiexin">
					<h4  class="wx-name"><?php echo zm_get_option('weixing_t'); ?></h4>
					<img title="微信" alt="微信" src="<?php echo zm_get_option('weixing_qr'); ?>"/>
				</div>
				<?php } else { ?>
				<div class="tcb-nline-wiexin"></div>
			<?php } ?>
			<div class="tcb-qq"><div></div><div></div><div></div><div></div><div></div></div>
		</div>
		<div class="arrow-right"></div>
	</div>
	<?php } ?>
</li>
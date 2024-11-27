<?php if ( ! defined( 'ABSPATH' ) ) exit;
if ( get_the_author_meta( 'qq' ) && is_single() && zm_get_option( 'author_qq' ) ) {
	$qq      = get_the_author_meta( 'qq' );
	$qq_name = '联系作者';
} else {
	$qq      = zm_get_option( 'qq_id' );
	$qq_name = zm_get_option( 'qq_name' );
}

if ( get_the_author_meta( 'weixinqr' ) && is_single() && zm_get_option( 'author_qq' ) ) {
	$weixinqr  = get_the_author_meta( 'weixinqr' );
	$weixing_t = '微信';

} else {
	$weixinqr  = zm_get_option( 'weixing_qr' );
	$weixing_t = zm_get_option( 'weixing_t' );
}

if ( get_the_author_meta( 'phone' ) && is_single() && zm_get_option( 'author_qq' ) ) {
	$phone = get_the_author_meta( 'phone' );
} else {
	$phone = zm_get_option( 'm_phone' );
}
?>

<?php if ( wp_is_mobile() ) { ?>
	<?php if ( zm_get_option( 'm_phone' ) == '' ) { ?>
		<li class="phone-mobile foh"><a class="fo" target="_blank" rel="external nofollow" href="tel:<?php echo $phone; ?>"><i class="be be-phone"></i></a></li>
	<?php } ?>
<?php } ?>
<li class="qqonline foh">
	<?php if ( wp_is_mobile() ) { ?>
		<a class="qq-mobile quoteqq fo" href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq; ?>&site=qq&menu=yes" onclick="copyToClipboard(this)" target="_blank" rel="external nofollow"><i class="be be-qq"></i></a>
	<?php } else { ?>
	<div class="online">
		<a class="ms fo"><i class="be be-qq"></i></a>
	</div>
	<div class="qqonline-box qq-b">
		<div class="qqonline-main popup">
			<div class="tcb-qq"><div></div><div></div><div></div><div></div><div></div></div>
			<h4 class="qq-name"><?php if ( ! zm_get_option( 'qq_name' ) == '' ) { ?><?php echo $qq_name; ?><?php } ?></h4>

			<?php if ( ! zm_get_option( 'm_phone' ) == '' ) { ?>
				<div class="nline-phone">
					<i class="be be-phone"></i><?php echo $phone; ?>
				</div>
			<?php } ?>

			<?php if ( ! zm_get_option( 'qq_id' ) == '' ) { ?>
			<div class="nline-qq">
				<div class="qq-wpa qq-wpa-go">
					<a class="quoteqq" href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq; ?>&site=qq&menu=yes" onclick="copyToClipboard(this)" title="QQ在线咨询" target="_blank" rel="external nofollow"><i class="be be-qq ms"></i><span class="qq-wpa-t">QQ在线咨询</span></a>
				</div>
			</div>
			<?php } ?>

			<?php if ( zm_get_option( 'weixing_qr' ) ) { ?>
				<div class="nline-wiexin">
					<h4  class="wx-name"><?php echo $weixing_t; ?></h4>
					<img title="微信" alt="微信" src="<?php echo $weixinqr; ?>">
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
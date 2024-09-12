<?php 
include_once('../../../../../wp-config.php');
$scope = 'snsapi_login';
$mbt_weixin_id    = zm_get_option('weixin_id');
$mbt_weixin_secret    = zm_get_option('weixin_secret');
if (isset($_REQUEST['code']) && isset($_REQUEST['state'])){
	$beginWeixinLogin = new BeginloginWEIXIN();
	$beginWeixinLogin->login($mbt_weixin_id,$mbt_weixin_secret,$_REQUEST['code']);
	$beginWeixinLogin->weixin_cb();
}
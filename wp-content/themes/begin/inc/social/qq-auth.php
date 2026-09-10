<?php 
include_once('../../../../../wp-config.php');
$appid = zm_get_option('qq_app_id');
$appkey = zm_get_option('qq_key');
$callback = new BeginloginQQ();
$callback->callback($appid,$appkey,get_template_directory_uri().'/inc/social/qq-auth.php');
$callback->get_openid();
$callback->qq_cb();
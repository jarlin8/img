<?php 
include_once('../../../../../wp-config.php');
$appid = zm_get_option('weibo_key');
$appkey = zm_get_option('weibo_secret');
$callback = new BeginloginWEIBO();
$callback->callback($appid,$appkey,get_template_directory_uri().'/inc/social/sina-auth.php');
$callback->sina_cb();
<?php 
include_once('../../../../../wp-config.php');
$appid =zm_get_option('weibo_key');
$login = new BeginloginWEIBO();
$login->login($appid,get_template_directory_uri().'/inc/social/sina-auth.php');
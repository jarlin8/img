<?php 
include_once('../../../../../wp-config.php');
$scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo';
$appid = zm_get_option('qq_app_id');
$login = new BeginloginQQ();
$login->login($appid,$scope,get_template_directory_uri().'/inc/social/qq-auth.php');
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 普通收录
function Baidu_Submit($post_ID) {
	$WEB_TOKEN = zm_get_option('link_token');
	$WEB_DOMAIN = home_url();
	if (get_post_meta($post_ID,'Baidusubmit',true) == 1) return;
	$url = get_permalink($post_ID);
	$api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$WEB_TOKEN;
	$ch  = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => $url,
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = json_decode(curl_exec($ch),true);
	if (array_key_exists('success',$result)) {
		add_post_meta($post_ID, 'Baidusubmit', 1, true);
	}
}
add_action('publish_post', 'Baidu_Submit', 0);
//add_action('edit_post', 'Baidu_Submit', 0);